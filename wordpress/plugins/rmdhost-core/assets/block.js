/* RMDHost section block – editor UI (no build step; uses WordPress globals). */
(function (wp) {
  'use strict';
  var el = wp.element.createElement;
  var useState = wp.element.useState, useEffect = wp.element.useEffect, Fragment = wp.element.Fragment;
  var __ = wp.i18n.__;
  var be = wp.blockEditor, c = wp.components;
  var data = window.RMDHostBlock || { sections: {}, fields: {}, defaults: {}, groups: {}, icons: {}, styles: [] };

  function options(obj, empty) {
    var out = empty ? [{ value: '', label: empty }] : [];
    Object.keys(obj || {}).forEach(function (k) { out.push({ value: k, label: obj[k] }); });
    return out;
  }

  function stringify(v) { return Array.isArray(v) ? v.join('\n') : (v == null ? '' : String(v)); }

  function Field(props) {
    var key = props.name, f = props.field, type = f[0], label = f[1];
    var value = props.value, def = props.def, set = props.onChange;
    switch (type) {
      case 'switch':
        return el(c.ToggleControl, { label: label, checked: value === undefined ? !!def : !!value, onChange: set, __nextHasNoMarginBottom: true });
      case 'group':
      case 'faq_group':
      case 'select':
        var opts = type === 'select' ? options(f.options) : (type === 'faq_group' ? [{ value: 'general', label: __('General (homepage)', 'rmdhost-core') }].concat(options(data.groups)) : options(data.groups, '—'));
        return el(c.SelectControl, { label: label, value: value === undefined ? (def || '') : value, options: opts, onChange: set, __nextHasNoMarginBottom: true });
      case 'groups':
        var cur = value === undefined ? (def || []) : value;
        return el('fieldset', { className: 'rmd-groups' }, el('legend', null, label), Object.keys(data.groups).map(function (g) {
          return el(c.CheckboxControl, {
            key: g, label: data.groups[g], checked: cur.indexOf(g) > -1, __nextHasNoMarginBottom: true,
            onChange: function (on) { var n = cur.filter(function (x) { return x !== g; }); if (on) n.push(g); set(n); },
          });
        }));
      case 'image':
        return el(be.MediaUploadCheck, null, el(be.MediaUpload, {
          allowedTypes: ['image'], value: typeof value === 'number' ? value : undefined,
          onSelect: function (m) { set(m.id); },
          render: function (o) {
            return el('div', { className: 'rmd-media' },
              el('p', null, label),
              el(c.Button, { variant: 'secondary', onClick: o.open }, value ? __('Replace image', 'rmdhost-core') : __('Choose image', 'rmdhost-core')),
              value ? el(c.Button, { variant: 'link', isDestructive: true, onClick: function () { set(undefined); } }, __('Use default', 'rmdhost-core')) : null);
          },
        }));
      case 'repeater':
        return el(RepeaterJSON, { label: label, value: value === undefined ? def : value, onChange: set, fields: f.fields });
      case 'textarea':
      case 'lines':
        return el(c.TextareaControl, { label: label, value: value === undefined ? '' : stringify(value), placeholder: stringify(def), help: type === 'lines' ? __('One item per line.', 'rmdhost-core') : undefined, onChange: function (v) { set(v === '' ? undefined : (type === 'lines' ? v.split('\n') : v)); }, __nextHasNoMarginBottom: true });
      default:
        return el(c.TextControl, { label: label, type: type === 'number' ? 'number' : 'text', value: value === undefined ? '' : String(value), placeholder: stringify(def), onChange: function (v) { set(v === '' ? undefined : v); }, __nextHasNoMarginBottom: true });
    }
  }

  function RepeaterJSON(props) {
    var initial = JSON.stringify(props.value || [], null, 2);
    var st = useState(initial), text = st[0], setText = st[1];
    var er = useState(''), err = er[0], setErr = er[1];
    useEffect(function () { setText(JSON.stringify(props.value || [], null, 2)); }, [JSON.stringify(props.value)]);
    return el('div', { className: 'rmd-repeater' },
      el(c.TextareaControl, {
        label: props.label + ' (JSON)', rows: 10, value: text, __nextHasNoMarginBottom: true,
        help: err || __('Fields:', 'rmdhost-core') + ' ' + Object.keys(props.fields).join(', '),
        onChange: function (v) {
          setText(v);
          try { var parsed = JSON.parse(v); if (!Array.isArray(parsed)) throw new Error('array'); setErr(''); props.onChange(parsed); }
          catch (e) { setErr(__('Invalid JSON – changes not applied yet.', 'rmdhost-core')); }
        },
      }));
  }

  function Preview(props) {
    var st = useState(null), html = st[0], setHtml = st[1];
    var key = JSON.stringify([props.section, props.args]);
    useEffect(function () {
      var live = true;
      var t = setTimeout(function () {
        wp.apiFetch({ path: '/rmdhost-core/v1/render', method: 'POST', data: { section: props.section, args: props.args || {} } })
          .then(function (r) { if (live) setHtml(r.html || ''); })
          .catch(function () { if (live) setHtml('<p style="padding:24px">' + __('Preview unavailable.', 'rmdhost-core') + '</p>'); });
      }, 350);
      return function () { live = false; clearTimeout(t); };
    }, [key]);
    if (html === null) return el(c.Placeholder, { label: data.sections[props.section] }, el(c.Spinner));
    var head = (data.styles || []).map(function (s) { return '<link rel="stylesheet" href="' + s + '">'; }).join('') +
      '<style>body{margin:0;background:#fff}[data-reveal]{opacity:1!important;transform:none!important}.subnav{position:static!important}</style>';
    return el('div', { className: 'rmdhost-block-preview' }, el(c.SandBox, { html: head + html, scripts: data.script ? [data.script] : [], title: data.sections[props.section] }));
  }

  wp.blocks.registerBlockType('rmdhost/section', {
    apiVersion: 3,
    title: __('RMDHost section', 'rmdhost-core'),
    description: __('Any RMDHost theme section – hero, pricing tabs, plans, map, FAQ, CTA…', 'rmdhost-core'),
    category: 'rmdhost',
    icon: 'cloud',
    keywords: ['rmdhost', 'pricing', 'hero', 'server', 'plans'],
    attributes: { section: { type: 'string', default: '' }, args: { type: 'object', default: {} }, align: { type: 'string', default: 'full' } },
    supports: { html: false, align: ['full'] },
    edit: function (props) {
      var a = props.attributes, section = a.section, args = a.args || {};
      var blockProps = be.useBlockProps ? be.useBlockProps() : {};
      if (!data.ready) {
        return el('div', blockProps, el(c.Notice, { status: 'warning', isDismissible: false }, __('Activate the RMDHost theme to use RMDHost sections.', 'rmdhost-core')));
      }
      var picker = el(c.SelectControl, {
        label: __('Section', 'rmdhost-core'), value: section, options: options(data.sections, __('— Choose a section —', 'rmdhost-core')),
        onChange: function (v) { props.setAttributes({ section: v, args: {} }); }, __nextHasNoMarginBottom: true,
      });
      if (!section) {
        return el('div', blockProps, el(c.Placeholder, { icon: 'cloud', label: __('RMDHost section', 'rmdhost-core'), instructions: __('Pick a section. Edit its content in the sidebar.', 'rmdhost-core') }, picker));
      }
      var fields = data.fields[section] || {}, defs = data.defaults[section] || {};
      var setArg = function (k) { return function (v) { var n = Object.assign({}, args); if (v === undefined) delete n[k]; else n[k] = v; props.setAttributes({ args: n }); }; };
      return el(Fragment, null,
        el(be.InspectorControls, null,
          el(c.PanelBody, { title: __('Section', 'rmdhost-core'), initialOpen: true }, picker,
            el(c.Button, { variant: 'link', isDestructive: true, onClick: function () { props.setAttributes({ args: {} }); } }, __('Reset to defaults', 'rmdhost-core'))),
          Object.keys(fields).length ? el(c.PanelBody, { title: __('Content', 'rmdhost-core'), initialOpen: true },
            Object.keys(fields).map(function (k) {
              return el('div', { key: k, style: { marginBottom: '16px' } }, el(Field, { name: k, field: fields[k], value: args[k], def: defs[k], onChange: setArg(k) }));
            })) : null),
        el('div', blockProps, el(Preview, { section: section, args: args })));
    },
    save: function () { return null; },
  });
})(window.wp);
