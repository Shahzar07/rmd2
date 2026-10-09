<?php
/**
 * Default content for each section. Every value can be overridden by the
 * Customizer (homepage), Elementor widget controls or block attributes.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registered sections (slug => label). Elementor widgets and the block editor
 * build their pickers from this list.
 *
 * @return array
 */
function rmdhost_sections() {
	$sections = array(
		'hero'         => __( 'Homepage hero', 'rmdhost' ),
		'promo'        => __( 'Promo cards', 'rmdhost' ),
		'finder'       => __( 'Server finder prompt', 'rmdhost' ),
		'tools'        => __( 'Feature tabs', 'rmdhost' ),
		'essentials'   => __( 'Essentials grid', 'rmdhost' ),
		'alt'          => __( 'Alternating rows', 'rmdhost' ),
		'support'      => __( '24/7 support', 'rmdhost' ),
		'pricing'      => __( 'Pricing tabs', 'rmdhost' ),
		'automation'   => __( 'Automation + product carousel', 'rmdhost' ),
		'locations'    => __( 'Data-centre map', 'rmdhost' ),
		'testimonials' => __( 'Testimonials wall', 'rmdhost' ),
		'faq'          => __( 'FAQ', 'rmdhost' ),
		'cta'          => __( 'Call to action', 'rmdhost' ),
		'product-hero' => __( 'Product hero', 'rmdhost' ),
		'plans'        => __( 'Product plans', 'rmdhost' ),
		'use-tabs'     => __( 'Product use cases', 'rmdhost' ),
		'features'     => __( 'Feature bento', 'rmdhost' ),
		'apps'         => __( 'OS & one-click apps', 'rmdhost' ),
		'carousel'     => __( 'Product carousel', 'rmdhost' ),
		'all-plans'    => __( 'All plans (pricing page)', 'rmdhost' ),
		'steps'        => __( 'Numbered steps', 'rmdhost' ),
		'cards'        => __( 'Cards / stats / photo', 'rmdhost' ),
		'photo'        => __( 'Captioned photo', 'rmdhost' ),
		'references'   => __( 'References + reviews', 'rmdhost' ),
		'status'       => __( 'Network status', 'rmdhost' ),
		'contact'      => __( 'Contact routes + form', 'rmdhost' ),
		'kb'           => __( 'Knowledge base', 'rmdhost' ),
		'tutorials'    => __( 'Tutorials', 'rmdhost' ),
		'faq-all'      => __( 'All FAQs', 'rmdhost' ),
		'heading'      => __( 'Section heading', 'rmdhost' ),
	);
	/**
	 * Filters the available sections.
	 *
	 * @param array $sections slug => label.
	 */
	return apply_filters( 'rmdhost/sections', $sections );
}

/**
 * Defaults for a section.
 *
 * @param string $name Section slug.
 * @return array
 */
function rmdhost_section_defaults( $name ) {
	/**
	 * Filters a section's default arguments.
	 *
	 * @param array  $defaults Defaults.
	 * @param string $name     Section slug.
	 */
	return (array) apply_filters( 'rmdhost/section_defaults', rmdhost_section_base_defaults( $name ), $name );
}

/**
 * Built-in defaults for a section.
 *
 * @param string $name Section slug.
 * @return array
 */
function rmdhost_section_base_defaults( $name ) {
	switch ( $name ) {
		case 'hero':
			$home  = RMDHost\Data::catalog()['homepage'] ?? array();
			$h     = $home['hero'] ?? array();
			$b     = $home['banner'] ?? array();
			$r     = $b['rating'] ?? array();
			$mod   = static function ( $key, $fallback ) {
				return get_theme_mod( 'rmdhost_' . $key, $fallback );
			};
			$stats = array();
			foreach ( (array) ( $home['stats'] ?? array() ) as $i => $s ) {
				$v = $mod( "stat_{$i}_value", $s['value'] );
				$l = $mod( "stat_{$i}_label", $s['label'] );
				if ( '' !== $v && '' !== $l ) {
					$stats[] = array(
						'value' => $v,
						'label' => $l,
					);
				}
			}
			return array(
				'eyebrow'         => $mod( 'hero_eyebrow', $h['eyebrow'] ?? '' ),
				'headline'        => $mod( 'hero_headline', $h['headline'] ?? '' ),
				'text'            => $mod( 'hero_text', $h['text'] ?? '' ),
				'offer_label'     => $mod( 'hero_offer_label', $h['offer']['label'] ?? '' ),
				'offer_price'     => $mod( 'hero_offer_price', $h['offer']['price'] ?? '' ),
				'offer_suffix'    => $mod( 'hero_offer_suffix', $h['offer']['suffix'] ?? '/mo' ),
				'offer_note'      => $mod( 'hero_offer_note', $h['offer']['note'] ?? '' ),
				'cta1_label'      => $mod( 'hero_cta1_label', $h['primaryCta']['label'] ?? '' ),
				'cta1_url'        => $mod( 'hero_cta1_url', $h['primaryCta']['href'] ?? '' ),
				'cta2_label'      => $mod( 'hero_cta2_label', $h['secondaryCta']['label'] ?? '' ),
				'cta2_url'        => $mod( 'hero_cta2_url', $h['secondaryCta']['href'] ?? '' ),
				'stats'           => $stats,
				'banner'          => (bool) $mod( 'banner_enabled', ! empty( $b['enabled'] ) ),
				'banner_tag'      => $mod( 'banner_tag', $b['offerTag'] ?? '' ),
				'banner_text'     => $mod( 'banner_text', $b['offerText'] ?? '' ),
				'banner_cta'      => $mod( 'banner_cta_label', $b['offerCta']['label'] ?? '' ),
				'banner_cta_url'  => $mod( 'banner_cta_url', $b['offerCta']['href'] ?? '' ),
				'rating_label'    => $mod( 'rating_label', $r['label'] ?? '' ),
				'rating_note'     => $mod( 'rating_note', $r['note'] ?? '' ),
				'rating_score'    => $mod( 'rating_score', $r['score'] ?? '' ),
				'rating_scale'    => $mod( 'rating_scale', $r['scale'] ?? 5 ),
				'rating_count'    => $mod( 'rating_count', $r['count'] ?? '' ),
				'rating_url'      => $mod( 'rating_url', $r['url'] ?? '' ),
				'rating_linktext' => $mod( 'rating_linktext', $r['linkText'] ?? __( 'Read reviews', 'rmdhost' ) ),
			);

		case 'promo':
			return array(
				'big_tag'    => __( 'Pricing', 'rmdhost' ),
				'big_title'  => __( 'Plans and prices', 'rmdhost' ),
				'big_text'   => __( 'KVM Linux VPS from {price:vps}, Ryzen Windows VPS, OpenStack NVMe VPS and bare-metal Xeon servers – monthly billing, no setup fee on Linux &amp; SSD VPS.', 'rmdhost' ),
				'big_button' => __( 'Explore all offers', 'rmdhost' ),
				'big_url'    => '/pricing/',
				'big_image'  => 'datacentre-building',
				'cards'      => array(
					array(
						'tag'   => __( 'New', 'rmdhost' ),
						'title' => __( 'AWS Lightsail VPS', 'rmdhost' ),
						'text'  => __( 'Fixed-price cloud bundles: 2 vCPUs, 1–8 GB RAM and 2–5 TB transfer from {price:aws-lightsail-vps}.', 'rmdhost' ),
						'url'   => '/aws-lightsail-vps/',
						'icon'  => '',
					),
					array(
						'tag'   => __( 'Trending', 'rmdhost' ),
						'title' => __( 'Instant dedicated servers', 'rmdhost' ),
						'text'  => __( 'Bare metal in the USA, online in minutes from {price:instant-dedicated-servers-usa}.', 'rmdhost' ),
						'url'   => '/instant-dedicated-servers-usa/',
						'icon'  => 'rocket',
					),
				),
			);

		case 'finder':
			return array(
				'title'   => __( 'Tell us your project.<br><span class="grad-text">We’ll pick the perfect server.</span>', 'rmdhost' ),
				'note'    => __( 'Get an instant server recommendation. Free, no signup.', 'rmdhost' ),
				'phrases' => array(
					__( 'A Minecraft server for 100 players', 'rmdhost' ),
					__( 'WordPress sites for my agency', 'rmdhost' ),
					__( 'MetaTrader bots running 24/7', 'rmdhost' ),
					__( 'A private ChatGPT for my team', 'rmdhost' ),
					__( 'A Postgres database for my SaaS', 'rmdhost' ),
					__( 'iOS builds for my app', 'rmdhost' ),
				),
			);

		case 'tools':
			return array(
				'title'      => __( 'Servers for every online project', 'rmdhost' ),
				'text'       => __( 'Get more power and control over what you run online, without taking on more technical complexity.', 'rmdhost' ),
				'tabs'       => array(
					array(
						'label' => __( 'Deploy', 'rmdhost' ),
						'scene' => 'terminal',
						'icon'  => 'rocket',
						'title' => __( 'Provision in seconds, not tickets.', 'rmdhost' ),
						'text'  => __( 'Pick a plan, OS image and data centre. Your KVM instance boots with root SSH access, IPv4 + IPv6 /64 and DDoS filtering already applied.', 'rmdhost' ),
						'link'  => __( 'Deploy a VPS', 'rmdhost' ),
						'url'   => '/vps/',
					),
					array(
						'label' => __( 'Scale', 'rmdhost' ),
						'scene' => 'dashboard',
						'icon'  => 'scale',
						'title' => __( 'Scale from 1 vCore to bare metal.', 'rmdhost' ),
						'text'  => __( 'Resize vCPU, RAM and disk from the client area, attach extra IPs, or move to a dedicated Xeon box when load demands it – our engineers handle the migration.', 'rmdhost' ),
						'link'  => __( 'Compare plans', 'rmdhost' ),
						'url'   => '/pricing/',
					),
					array(
						'label' => __( 'Protect', 'rmdhost' ),
						'scene' => 'shield',
						'icon'  => 'shield',
						'title' => __( 'Filtered at the edge.', 'rmdhost' ),
						'text'  => __( 'Volumetric and protocol attacks are scrubbed upstream before they reach your port. Game servers add UDP-aware mitigation for Minecraft, CS and ARMA.', 'rmdhost' ),
						'link'  => __( 'How DDoS protection works', 'rmdhost' ),
						'url'   => '/ddos-protection/',
					),
					array(
						'label' => __( 'Manage', 'rmdhost' ),
						'scene' => 'windows',
						'icon'  => 'panel',
						'title' => __( 'Root, RDP and out-of-band console.', 'rmdhost' ),
						'text'  => __( 'SSH or Remote Desktop in, use the VNC console when the network is down, snapshot before risky changes and reinstall any of 25+ OS images.', 'rmdhost' ),
						'link'  => __( 'Explore Windows VPS', 'rmdhost' ),
						'url'   => '/windows-vps/',
					),
				),
				'social'     => __( 'clients choose', 'rmdhost' ),
				'social_num' => '10K+',
				'sub_title'  => __( 'Want more hands-on control?', 'rmdhost' ),
				'cards'      => array(
					array(
						'icon'  => 'rack',
						'title' => __( 'Dedicated servers', 'rmdhost' ),
						'text'  => __( 'Bare-metal Xeon, fully under your control.', 'rmdhost' ),
						'url'   => '/dedicated-servers/',
					),
					array(
						'icon'  => 'game',
						'title' => __( 'Game servers', 'rmdhost' ),
						'text'  => __( 'Anti-DDoS Game for Minecraft, CS2, ARMA and more.', 'rmdhost' ),
						'url'   => '/game-servers/',
					),
				),
			);

		case 'essentials':
			return array(
				'title' => __( 'Set up the essentials to go online', 'rmdhost' ),
				'items' => array(
					array(
						'group'  => 'vps',
						'title'  => __( 'Linux VPS', 'rmdhost' ),
						'text'   => __( 'KVM · full root · SSD + HDD · unmetered 100 Mbit/s.', 'rmdhost' ),
						'visual' => 'linux',
						'chip'   => 'srv-482 · running',
					),
					array(
						'group'  => 'windows-vps',
						'title'  => __( 'Windows VPS', 'rmdhost' ),
						'text'   => __( 'Ryzen vCPU · NVMe · RDP · free backups.', 'rmdhost' ),
						'visual' => 'windows',
						'chip'   => 'rdp :3389 · connected',
					),
					array(
						'group'  => 'dedicated-servers',
						'title'  => __( 'Dedicated servers', 'rmdhost' ),
						'text'   => __( 'Bare-metal Xeon & Core · RAID 1 · 1 Gbit/s.', 'rmdhost' ),
						'visual' => 'rack',
						'chip'   => 'uranus · e-2146g · online',
					),
					array(
						'group'  => 'owncloud-storage',
						'title'  => __( 'Private cloud storage', 'rmdhost' ),
						'text'   => __( 'OwnCloud · E2E encryption · up to 1.6 TB.', 'rmdhost' ),
						'visual' => 'storage',
						'chip'   => 'sync · 2,481 objects',
					),
				),
			);

		case 'alt':
			return array(
				'rows' => array(
					array(
						'title' => __( 'Grow without limits and keep more of what you earn', 'rmdhost' ),
						'text'  => __( 'Unlimited bandwidth on VPS and dedicated plans, no setup fees and transparent monthly pricing in GBP or USD.', 'rmdhost' ),
						'link'  => __( 'See all pricing', 'rmdhost' ),
						'url'   => '/pricing/',
						'scene' => 'rack',
					),
					array(
						'title' => __( 'Bring AI in-house with a private AI VPS', 'rmdhost' ),
						'text'  => __( 'Run open-source models, AI agents and n8n automations on your own server – your prompts and data never leave it.', 'rmdhost' ),
						'link'  => __( 'Explore AI VPS', 'rmdhost' ),
						'url'   => '/ai-vps/',
						'scene' => 'ai',
					),
				),
			);

		case 'support':
			return array(
				'award'      => __( '24/7/365<br>Expert support', 'rmdhost' ),
				'title'      => __( 'Real engineers. Real answers.<br>Every hour of every day.', 'rmdhost' ),
				'text'       => __( 'Migrate, fix, secure and scale your servers with help from our in-house engineers – no chatbots, no scripts.', 'rmdhost' ),
				'button'     => __( 'Open a ticket', 'rmdhost' ),
				'button_url' => '/support/',
				'cards'      => array(
					array(
						'title' => __( 'Migrate', 'rmdhost' ),
						'text'  => __( 'Tell us where your site lives today and our engineers move it to RMDHost for free.', 'rmdhost' ),
						'q'     => __( 'Can you move my WordPress site from my old host?', 'rmdhost' ),
						'a'     => __( 'Of course – send us read-only access and we’ll migrate it with zero downtime.', 'rmdhost' ),
					),
					array(
						'title' => __( 'Secure', 'rmdhost' ),
						'text'  => __( 'Get help hardening SSH, firewalls and updates on your new server.', 'rmdhost' ),
						'q'     => __( 'How do I lock down SSH on my VPS?', 'rmdhost' ),
						'a'     => __( 'Use key-only login and disable root. I’ve added a step-by-step guide to your ticket.', 'rmdhost' ),
					),
					array(
						'title' => __( 'Fix', 'rmdhost' ),
						'text'  => __( 'Share your error and get clear steps to sort it out – day or night.', 'rmdhost' ),
						'q'     => __( 'My site is showing a 502 Bad Gateway. Can you help?', 'rmdhost' ),
						'a'     => __( 'Let me check your Nginx and PHP-FPM logs – I’ll find what’s causing it.', 'rmdhost' ),
					),
					array(
						'title' => __( 'Speed up', 'rmdhost' ),
						'text'  => __( 'Describe your speed issue and get tuning tips for your stack.', 'rmdhost' ),
						'q'     => __( 'Why is my WooCommerce store slow?', 'rmdhost' ),
						'a'     => __( 'Your MySQL buffer is too small. Increasing it to 2 GB should halve load time.', 'rmdhost' ),
					),
					array(
						'title' => __( 'Scale', 'rmdhost' ),
						'text'  => __( 'Ask for an upgrade path that matches your growth and budget.', 'rmdhost' ),
						'q'     => __( 'We’re expecting 10× traffic next month.', 'rmdhost' ),
						'a'     => __( 'A Premium Berg VPS or the Uranus dedicated server will handle it – here’s a quote.', 'rmdhost' ),
					),
				),
				'more_title' => __( 'Same team. More ways to grow.', 'rmdhost' ),
				'more_text'  => __( 'Every plan includes 24/7 support. Need more? Add fully managed services, custom hardware builds and dedicated account management.', 'rmdhost' ),
				'more_link'  => __( 'Learn more', 'rmdhost' ),
				'more_url'   => '/support/',
				'image'      => 'support-engineer',
				'chips'      => array( __( 'Install cPanel on my server', 'rmdhost' ), __( 'Restore last night’s snapshot', 'rmdhost' ), __( 'Add 4 IPs in Frankfurt', 'rmdhost' ) ),
			);

		case 'pricing':
			return array(
				'title'  => __( 'Choose the plan that<br>matches what you need', 'rmdhost' ),
				'text'   => __( 'Compare vCPU, RAM, storage, bandwidth and network protection side by side – every package links straight to checkout.', 'rmdhost' ),
				'assure' => array( __( 'Anti-DDoS on VPS', 'rmdhost' ), __( 'Monthly billing', 'rmdhost' ), __( '24/7 support', 'rmdhost' ) ),
				'groups' => array( 'vps', 'windows-vps', 'ssd-vps', 'dedicated-servers' ),
				'labels' => array(),
				'limit'  => 4,
				'fine'   => __( 'Prices exclude VAT. All plans are billed monthly. Unlimited bandwidth is subject to our Acceptable Use Policy.', 'rmdhost' ),
				'id'     => 'pricing',
				'soft'   => true,
			);

		case 'automation':
			return array(
				'title'          => __( 'Put your servers to work', 'rmdhost' ),
				'text'           => __( 'Launch AI tools, dev stacks and popular apps in a few clicks – then let them run in the background, around the clock.', 'rmdhost' ),
				'flow_title'     => __( 'One-click apps', 'rmdhost' ),
				'flow_text'      => __( 'Connect your tools, hand repetitive tasks to AI and keep things running – even when you’re not.', 'rmdhost' ),
				'links'          => array(
					array(
						'label' => __( 'Self-hosted n8n', 'rmdhost' ),
						'url'   => '/ai-vps/',
					),
					array(
						'label' => __( 'Ollama + Open WebUI', 'rmdhost' ),
						'url'   => '/ai-vps/',
					),
					array(
						'label' => __( 'Docker & Compose', 'rmdhost' ),
						'url'   => '/tutorials/',
					),
				),
				'carousel_title' => __( 'More power when you need it', 'rmdhost' ),
			);

		case 'locations':
			return array(
				'kicker'     => __( '// Global network', 'rmdhost' ),
				'title'      => __( '6 data centres. UK, EU & US.', 'rmdhost' ),
				'text'       => __( 'Deploy close to your users in Derby, Leeds, Amsterdam, Frankfurt, Riga or New York. Every site is staffed 24/7 with CCTV, fob-controlled access, VESDA fire detection and N+1 diesel backup power.', 'rmdhost' ),
				'button'     => __( 'Data-centre details', 'rmdhost' ),
				'button_url' => '/data-centres/',
				'stats'      => true,
				'status'     => __( 'Online', 'rmdhost' ),
			);

		case 'testimonials':
			return array(
				'title'       => __( 'Trusted by teams that can’t<br>afford downtime', 'rmdhost' ),
				'text'        => __( 'From agencies and traders to game networks and SaaS startups.', 'rmdhost' ),
				'button'      => __( 'See customer stories', 'rmdhost' ),
				'button_url'  => '/references/',
				'tiles'       => array(
					array(
						'video' => 'tile-gamer',
						'tag'   => __( 'Game Servers', 'rmdhost' ),
						'cap'   => __( 'Game<br>networks', 'rmdhost' ),
						'url'   => '/game-servers/',
						'tall'  => true,
					),
					array(
						'photo' => 'typing-hands',
						'tag'   => __( 'SSD VPS', 'rmdhost' ),
						'cap'   => __( 'Developers', 'rmdhost' ),
						'url'   => '/ssd-vps/',
					),
					array(
						'photo' => 'agency-team',
						'tag'   => __( 'Linux VPS', 'rmdhost' ),
						'cap'   => __( 'Web<br>agencies', 'rmdhost' ),
						'url'   => '/vps/',
						'tall'  => true,
					),
					array(
						'photo' => 'founder',
						'tag'   => __( 'Dedicated', 'rmdhost' ),
						'cap'   => __( 'SaaS<br>startups', 'rmdhost' ),
						'url'   => '/dedicated-servers/',
					),
					array(
						'video' => 'tile-typing',
						'tag'   => __( 'Cloud VPS', 'rmdhost' ),
						'cap'   => __( 'Builders', 'rmdhost' ),
						'url'   => '/cloud-vps/',
						'tall'  => true,
					),
					array(
						'video' => 'tile-founder',
						'tag'   => __( 'AI VPS', 'rmdhost' ),
						'cap'   => __( 'AI-first<br>teams', 'rmdhost' ),
						'url'   => '/ai-vps/',
						'tall'  => true,
					),
				),
				'rating_text' => __( 'Read independent reviews of our infrastructure on <a href="https://hostadvice.com/hosting-company/digitalberg-reviews/" rel="noopener" target="_blank">HostAdvice</a>.', 'rmdhost' ),
			);

		case 'faq':
			return array(
				/* translators: %s: site name */
				'title' => sprintf( __( '%s hosting FAQs', 'rmdhost' ), get_bloginfo( 'name' ) ),
				'group' => 'general',
				'soft'  => true,
			);

		case 'cta':
			return array(
				'title'      => __( 'Imagined it.<br>Now deploy it.', 'rmdhost' ),
				'text'       => __( 'No setup fee on Linux & SSD VPS. Free migration help from our in-house engineers.', 'rmdhost' ),
				'button'     => __( 'Get started', 'rmdhost' ),
				'button_url' => '/pricing/',
				'image'      => 'business-owner',
				'domain'     => 'yourproject<b>.com</b>',
				'toast'      => __( 'Server online', 'rmdhost' ),
				'toast_note' => __( 'Deployed in 38 seconds', 'rmdhost' ),
				'prompt'     => __( 'Deploy a VPS in London', 'rmdhost' ),
			);

		case 'plans':
			return array(
				'group' => 'vps',
				'title' => '',
				'text'  => '',
				'every' => true,
				'fine'  => __( 'Prices exclude VAT and are billed monthly. Toggle GBP / USD in the header.', 'rmdhost' ),
				'id'    => 'plans',
			);

		case 'features':
			return array(
				'group' => 'vps',
				'title' => '',
				'photo' => '',
				'id'    => 'features',
				'soft'  => true,
			);

		case 'apps':
			return array(
				'group' => 'vps',
				'title' => __( 'One-click deployment', 'rmdhost' ),
				'text'  => __( 'Deploy popular operating systems, control panels and applications. Get up and running in minutes.', 'rmdhost' ),
				'id'    => 'os',
			);

		case 'heading':
			return array(
				'eyebrow' => '',
				'title'   => __( 'Section title', 'rmdhost' ),
				'text'    => '',
				'soft'    => false,
			);
		case 'product-hero':
			return array(
				'group'   => 'vps',
				'title'   => '',
				'lede'    => '',
				'eyebrow' => '',
				'subnav'  => true,
			);
		case 'use-tabs':
			return array( 'group' => 'vps' );
		case 'carousel':
			return array(
				'title'   => __( 'Explore more servers', 'rmdhost' ),
				'exclude' => '',
			);
		case 'steps':
			return array(
				'title' => __( 'How it works', 'rmdhost' ),
				'items' => array(),
				'soft'  => false,
			);
		case 'cards':
			return array(
				'title'   => '',
				'items'   => array(),
				'soft'    => false,
				'photo'   => '',
				'caption' => '',
				'stats'   => false,
			);
		case 'photo':
			return array(
				'image'   => 'datacentre-aisle',
				'alt'     => '',
				'caption' => '',
			);
		case 'references':
			return array( 'title' => __( 'What customers say', 'rmdhost' ) );
		case 'status':
			return array( 'title' => __( 'All systems operational', 'rmdhost' ) );
		case 'contact':
			return array(
				'photo' => 'support-engineer',
				'title' => __( 'Send us a message', 'rmdhost' ),
			);
		case 'all-plans':
			return array(
				'callout' => true,
				'fine'    => __( 'Prices exclude VAT and are billed monthly.', 'rmdhost' ),
			);
		case 'kb':
		case 'tutorials':
		case 'faq-all':
			return array();
	}
	return array();
}

/**
 * Replace {price:group} tokens with live "from" prices.
 *
 * @param string $text Text.
 * @return string HTML.
 */
function rmdhost_price_tokens( $text ) {
	return preg_replace_callback(
		'/\{price:([a-z0-9-]+)\}/',
		static function ( $m ) {
			return rmdhost_money( RMDHost\Data::from_price( $m[1] ) );
		},
		rmdhost_kses_inline( $text )
	);
}
