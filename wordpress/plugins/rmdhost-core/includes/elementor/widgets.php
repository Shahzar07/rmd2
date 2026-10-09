<?php
/**
 * One widget class per theme section.
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core\Widgets;

defined( 'ABSPATH' ) || exit;

/**
 * Widget for the "hero" section.
 */
class Hero extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'hero';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-header';
}

/**
 * Widget for the "promo" section.
 */
class Promo extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'promo';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-call-to-action';
}

/**
 * Widget for the "finder" section.
 */
class Finder extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'finder';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-search';
}

/**
 * Widget for the "tools" section.
 */
class Tools extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'tools';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-tabs';
}

/**
 * Widget for the "essentials" section.
 */
class Essentials extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'essentials';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-gallery-grid';
}

/**
 * Widget for the "alt" section.
 */
class Alt extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'alt';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-image-box';
}

/**
 * Widget for the "support" section.
 */
class Support extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'support';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-headphones';
}

/**
 * Widget for the "pricing" section.
 */
class Pricing extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'pricing';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-price-table';
}

/**
 * Widget for the "automation" section.
 */
class Automation extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'automation';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-flow';
}

/**
 * Widget for the "locations" section.
 */
class Locations extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'locations';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-map-pin';
}

/**
 * Widget for the "testimonials" section.
 */
class Testimonials extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'testimonials';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-testimonial-carousel';
}

/**
 * Widget for the "faq" section.
 */
class Faq extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'faq';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-accordion';
}

/**
 * Widget for the "cta" section.
 */
class Cta extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'cta';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-call-to-action';
}

/**
 * Widget for the "product-hero" section.
 */
class Product_Hero extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'product-hero';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-header';
}

/**
 * Widget for the "plans" section.
 */
class Plans extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'plans';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-price-table';
}

/**
 * Widget for the "use-tabs" section.
 */
class Use_Tabs extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'use-tabs';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-tabs';
}

/**
 * Widget for the "features" section.
 */
class Features extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'features';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-gallery-masonry';
}

/**
 * Widget for the "apps" section.
 */
class Apps extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'apps';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-apps';
}

/**
 * Widget for the "carousel" section.
 */
class Carousel extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'carousel';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-media-carousel';
}

/**
 * Widget for the "all-plans" section.
 */
class All_Plans extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'all-plans';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-price-list';
}

/**
 * Widget for the "steps" section.
 */
class Steps extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'steps';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-number-field';
}

/**
 * Widget for the "cards" section.
 */
class Cards extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'cards';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-info-box';
}

/**
 * Widget for the "photo" section.
 */
class Photo extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'photo';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-image';
}

/**
 * Widget for the "references" section.
 */
class References extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'references';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-testimonial';
}

/**
 * Widget for the "status" section.
 */
class Status extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'status';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-check-circle';
}

/**
 * Widget for the "contact" section.
 */
class Contact extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'contact';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-form-horizontal';
}

/**
 * Widget for the "kb" section.
 */
class Kb extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'kb';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-help-o';
}

/**
 * Widget for the "tutorials" section.
 */
class Tutorials extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'tutorials';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-post-list';
}

/**
 * Widget for the "faq-all" section.
 */
class Faq_All extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'faq-all';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-toggle';
}

/**
 * Widget for the "heading" section.
 */
class Heading extends Section_Widget {

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	protected $section = 'heading';

	/**
	 * Icon.
	 *
	 * @var string
	 */
	protected $icon = 'eicon-heading';
}
