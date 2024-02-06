<?php
return [
	'hero' => [
		'field_name' => [
			'title' => 'text',
			'sub_title' => 'text',
			'button_name' => 'text',
			'button_link' => 'url',
			'image1' => 'file',
			'image2' => 'file',
			'image3' => 'file',
		],
		'validation' => [
			'title.*' => 'required|max:100',
			'sub_title.*' => 'required|max:2000',
			'button_name.*' => 'required|max:2000',
			'button_link.*' => 'required|max:2000',
			'image1.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,gif,svg',
			'image2.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,gif,svg',
			'image3.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png,gif,svg',
		]
	],
	'about-us' => [
		'field_name' => [
			'slogan' => 'text',
			'title' => 'text',
			'sub_title' => 'text',
			'happy_clients' => 'text',
			'total_shipments' => 'text',
			'image1' => 'file',
			'image2' => 'file'
		],
		'validation' => [
			'slogan.*' => 'required|max:50',
			'title.*' => 'required|max:200',
			'sub_title.*' => 'required|max:3000',
			'image1.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
			'image2.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
		],
		'size' => [
			'image1' => '1916x1648',
			'image2' => '403x361'
		]
	],
	'services' => [
		'field_name' => [
			'slogan' => 'text',
			'title' => 'text',
			'short_description' => 'text',
		],
		'validation' => [
			'slogan.*' => 'required|min:2|max:100',
			'sub_title.*' => 'required|min:2|max:200',
			'short_description.*' => 'required|min:2|max:2000',
		],
	],
	'why-choose-us' => [
		'field_name' => [
			'slogan' => 'text',
			'title' => 'text',
			'image' => 'file'
		],
		'validation' => [
			'slogan.*' => 'required|max:50',
			'title.*' => 'required|max:200',
			'image.*' => 'nullable|max:3072|image|mimes:jpg,jpeg,png',
		]
	],
	'testimonial' => [
		'field_name' => [
			'slogan' => 'text',
			'title' => 'text',
		],
		'validation' => [
			'slogan.*' => 'required|max:50',
			'title.*' => 'required|max:200',
		]
	],
	'how-it-work' => [
		'field_name' => [
			'slogan' => 'text',
			'title' => 'text',
			'short_description' => 'text',
		],
		'validation' => [
			'slogan.*' => 'required|min:2|max:100',
			'sub_title.*' => 'required|min:2|max:200',
			'short_description.*' => 'required|min:2|max:2000',
		],
	],
	'faq' => [
		'field_name' => [
			'slogan' => 'text',
			'title' => 'text',
			'short_description' => 'text',
		],
		'validation' => [
			'slogan.*' => 'required|min:2|max:100',
			'title.*' => 'required|min:2|max:200',
			'short_description.*' => 'required|min:2|max:2000',
		],
	],
	'blog' => [
		'field_name' => [
			'slogan' => 'text',
			'title' => 'text',
		],
		'validation' => [
			'slogan.*' => 'required|min:2|max:100',
			'title.*' => 'required|min:2|max:200',
		],
	],
	'tracking' => [
		'field_name' => [
			'title' => 'text',
			'sub_title' => 'text',
		],
		'validation' => [
			'title.*' => 'required|max:200',
			'sub_title.*' => 'required|max:3000',
		],
	],
	'contact' => [
		'field_name' => [
			'title' => 'text',
			'sub_title' => 'text',
			'button_link' => 'url',
			'phone' => 'text',
			'email' => 'email',
			'address' => 'text',
			'about_company' => 'text',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
			'sub_title.*' => 'required|min:2|max:500',
			'address.*' => 'required|min:2|max:100',
			'email.*' => 'required|min:2|max:100',
			'phone.*' => 'required|min:2|max:100',
			'about_company.*' => 'required|min:2|max:1000',
		],
	],
	'packaging-cost' => [
		'field_name' => [
			'title' => 'text',
			'sub_title' => 'text',
		],
		'validation' => [
			'title.*' => 'required|max:500',
			'sub_title.*' => 'required|max:10000',
		]
	],

	'login' => [
		'field_name' => [
			'title' => 'text',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
		],
	],

	'register' => [
		'field_name' => [
			'title' => 'text',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
		],
	],

	'forget-password' => [
		'field_name' => [
			'title' => 'text',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
		],
	],
	'email-verification' => [
		'field_name' => [
			'title' => 'text',
			'sub_title' => 'text',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
			'sub_title.*' => 'required|min:2|max:100',
		],
	],

	'sms-verification' => [
		'field_name' => [
			'title' => 'text',
		],
		'validation' => [
			'title.*' => 'required|min:2|max:100',
		],
	],

	'message' => [
		'required' => 'This field is required.',
		'min' => 'This field must be at least :min characters.',
		'max' => 'This field may not be greater than :max characters.',
		'image' => 'This field must be image.',
		'mimes' => 'This image must be a file of type: jpg, jpeg, png.',
	],

	'template_media' => [
		'image' => 'file',
		'image1' => 'file',
		'image2' => 'file',
		'image3' => 'file',
		'background_image' => 'file',
		'thumbnail' => 'file',
		'youtube_link' => 'url',
		'button_link' => 'url',
	]
];
