<?php
return [
	'settings' => [
		'basic' => [
			'route' => 'basic.control',
			'icon' => 'fas fa-cog',
			'short_description' => 'Basic such as, site title, timezone, currency, notifications, verifications and so on.',
		],
		'logo-favicon-breadcrumb' => [
			'route' => 'logo.update',
			'icon' => 'fas fa-image',
			'short_description' => 'Logo settings such as, logo, footer logo, admin logo, favicon, breadcrumb.',
		],
		'push-notification' => [
			'route' => 'settings',
			'route_segment' => ['push-notification'],
			'icon' => 'fas fa-bullhorn',
			'short_description' => 'Push notification settings such as, firebase configuration and push notification templates.',
		],
		'in-app-notification' => [
			'route' => 'settings',
			'route_segment' => ['in-app-notification'],
			'icon' => 'far fa-bell',
			'short_description' => 'In app notification settings such as, pusher configuration and in app notification templates.',
		],
		'exchange_api' => [
			'route' => 'currency.exchange.api.config',
			'icon' => 'fas fa-exchange-alt',
			'short_description' => 'Currency Layer Access Key, Coin Market Cap App Key, Select update time and so on.',
		],
		'seo' => [
			'route' => 'seo.update',
			'icon' => 'fas fa-search-location',
			'short_description' => 'Meta keywords, meta description, social title, social description, meta image and so on.',
		],
		'email' => [
			'route' => 'settings',
			'route_segment' => ['email'],
			'icon' => 'far fa-envelope',
			'short_description' => 'Email settings such as, email configuration and email templates.',
		],
		'sms' => [
			'route' => 'settings',
			'route_segment' => ['sms'],
			'icon' => 'far fa-comment',
			'short_description' => 'SMS settings such as, SMS configuration and SMS templates.',
		],
		'language' => [
			'route' => 'language.index',
			'icon' => 'fas fa-language',
			'short_description' => 'Language settings such as, create new language, add keywords and so on.',
		],
		'Storage' => [
			'route' => 'storage.index',
			'icon' => 'fas fa-box',
			'short_description' => 'Storage settings such as, store images.',
		],
		'plugin' => [
			'route' => 'plugin.config',
			'route_segment' => ['plugin'],
			'icon' => 'fas fa-toolbox',
			'short_description' => 'Message your customers, reCAPTCHA protects, google analytics your website and so on.',
		],
	],
	'plugin' => [
		'tawk-control' => [
			'route' => 'tawk.control',
			'icon' => 'fas fa-drumstick-bite',
			'short_description' => 'Message your customers,they\'ll love you for it',
		],
		'fb-messenger-control' => [
			'route' => 'fb.messenger.control',
			'icon' => 'fab fa-facebook-messenger',
			'short_description' => 'Message your customers,they\'ll love you for it',
		],
		'google-recaptcha-control' => [
			'route' => 'google.recaptcha.control',
			'icon' => 'fas fa-puzzle-piece',
			'short_description' => 'reCAPTCHA protects your website from fraud and abuse.',
		],
		'manual-recaptcha-control' => [
			'route' => 'manual.recaptcha.control',
			'icon' => 'fas fa-robot',
			'short_description' => 'Manual reCAPTCHA protects your website from fraud and abuse.',
		],

		'google-analytics-control' => [
			'route' => 'google.analytics.control',
			'icon' => 'fas fa-chart-line',
			'short_description' => 'Google Analytics is a web analytics service offered by Google.',
		],
	],
	'in-app-notification' => [
		'in-app-notification-controls' => [
			'route' => 'pusher.config',
			'icon' => 'far fa-bell',
			'short_description' => 'Setup pusher configuration for in app notifications.',
		],
		'notifcation-templates' => [
			'route' => 'notify.template.index',
			'icon' => 'fas fa-scroll',
			'short_description' => 'Setup in app notification templates',
		]
	],
	'push-notification' => [
		'push-notification-controls' => [
			'route' => 'firebase.config',
			'icon' => 'fas fa-bullhorn',
			'short_description' => 'Setup firebase configuration for push notifications.',
		],
		'notification-templates' => [
			'route' => 'push.notify.template.index',
			'icon' => 'fas fa-scroll',
			'short_description' => 'Setup push notification templates',
		]
	],
	'email' => [
		'email-configuration' => [
			'route' => 'email.config',
			'icon' => 'far fa-envelope-open',
			'short_description' => 'Email Config such as, sender email, email methods and so on.',
		],
		'default-templates' => [
			'route' => 'email.template.default',
			'icon' => 'far fa-envelope',
			'short_description' => 'Setup email templates for default email notifications.',
		],
		'email-templates' => [
			'route' => 'email.template.index',
			'icon' => 'fas fa-laptop-code',
			'short_description' => 'Setup email templates for different email notifications.',
		]
	],
	'sms' => [
		'sms-controls' => [
			'route' => 'sms.config',
			'icon' => 'far fa-comment-alt',
			'short_description' => 'Setup SMS api configuration for sending sms notifications.',
		],
		'sms-templates' => [
			'route' => 'sms.template.index',
			'icon' => 'fas fa-laptop-code',
			'short_description' => 'Setup sms templates for different email notifications.',
		]
	],

];
