<?php

namespace App\Http\Controllers;

use App\Models\BasicControl;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Models\Country;
use App\Models\DefaultShippingRateInternationally;
use App\Models\DefaultShippingRateOperatorCountry;
use App\Models\Language;
use App\Models\Package;
use App\Models\ParcelType;
use App\Models\Shipment;
use App\Models\Subscribe;
use App\Models\Template;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class FrontendController extends Controller
{
	use Notify;
	public function __construct()
	{
		$this->theme = template();
	}

	public function checkError(){
		return view($this->theme.'errors.500');
	}

	public function home()
	{
		$templateSection = ['hero', 'about-us', 'services', 'why-choose-us', 'testimonial', 'how-it-work', 'faq', 'blog', 'how-we-work', 'know-more-us', 'deposit-withdraw', 'news-letter', 'news-letter-referral', 'request-a-call', 'investor', 'we-accept', 'investment'];
		$data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

		$contentSection = ['hero', 'feature', 'services', 'why-choose-us', 'testimonial', 'how-it-work', 'faq', 'how-we-work', 'know-more-us', 'investor', 'blog'];
		$data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
			->whereHas('content', function ($query) use ($contentSection) {
				return $query->whereIn('name', $contentSection);
			})
			->with(['content:id,name',
				'content.contentMedia' => function ($q) {
					$q->select(['content_id', 'description', 'driver']);
				}])
			->get()->groupBy('content.name');

		$data['blogs'] = Blog::with(['details', 'category.details'])->where('status', 1)->take(3)->latest()->get();
		return view($this->theme . 'home', $data);
	}

	public function about()
	{
		$templateSection = ['about-us', 'why-choose-us', 'testimonial', 'investor', 'faq', 'we-accept', 'how-it-work', 'how-we-work', 'know-more-us'];
		$data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

		$contentSection = ['feature', 'why-choose-us', 'testimonial', 'investor', 'faq', 'how-it-work', 'how-we-work', 'know-more-us'];
		$data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
			->whereHas('content', function ($query) use ($contentSection) {
				return $query->whereIn('name', $contentSection);
			})
			->with(['content:id,name',
				'content.contentMedia' => function ($q) {
					$q->select(['content_id', 'description', 'driver']);
				}])
			->get()->groupBy('content.name');
		return view($this->theme . 'about', $data);
	}

	public function service()
	{
		$templateSection = ['about-us', 'services', 'why-choose-us', 'investor', 'faq', 'we-accept', 'how-it-work', 'how-we-work', 'know-more-us'];
		$data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

		$contentSection = ['feature', 'services', 'why-choose-us', 'investor', 'faq', 'how-it-work', 'how-we-work', 'know-more-us'];
		$data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
			->whereHas('content', function ($query) use ($contentSection) {
				return $query->whereIn('name', $contentSection);
			})
			->with(['content:id,name',
				'content.contentMedia' => function ($q) {
					$q->select(['content_id', 'description', 'driver']);
				}])
			->get()->groupBy('content.name');
		return view($this->theme . 'service', $data);
	}

	public function tracking(Request $request)
	{
		$search = $request->all();
		$templateSection = ['about-us', 'investor', 'faq', 'we-accept', 'how-it-work', 'how-we-work', 'know-more-us', 'tracking'];
		$data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

		$contentSection = ['feature', 'why-chose-us', 'investor', 'faq', 'how-it-work', 'how-we-work', 'know-more-us'];
		$data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
			->whereHas('content', function ($query) use ($contentSection) {
				return $query->whereIn('name', $contentSection);
			})
			->with(['content:id,name',
				'content.contentMedia' => function ($q) {
					$q->select(['content_id', 'description']);
				}])
			->get()->groupBy('content.name');

		if (sizeof($search) > 0){
			$data['shipment'] = Shipment::with('senderBranch', 'receiverBranch')->where('shipment_id', $search['shipment_id'])->first();
			$data['initial'] = false;
		}else{
			$data['shipment'] = null;
			$data['initial'] = true;
		}

		return view($this->theme . 'tracking', $data);
	}

	public function shippingCalculatorOperatorCountry(){
		$data['shipmentTypeList'] = config('shipmentTypeList');
		$data['parcelTypes'] = ParcelType::where('status', 1)->get();
		$basicControl = BasicControl::with('operatorCountry')->first();

		if(!$basicControl->operatorCountry){
			return redirect('/')->with('error','Admin has not declared Operator country');
		}

		$defaultShippingRateOC = DefaultShippingRateOperatorCountry::firstOrNew([
			'country_id' => optional($basicControl->operatorCountry)->id
		]);
		$defaultShippingRateOC->save();



		$data['defaultShippingRateOC'] = DefaultShippingRateOperatorCountry::firstOrFail();

		$data['basicControl'] = $basicControl;


		return view($this->theme . 'operatorCountryCalculator', $data);
	}

	public function shippingCalculatorInternationally(){
		$data['allCountries'] = Country::where('status', 1)->get();
		$data['shipmentTypeList'] = config('shipmentTypeList');
		$data['parcelTypes'] = ParcelType::where('status', 1)->get();
		$data['defaultShippingRateInternationally'] = DefaultShippingRateInternationally::first();
		return view($this->theme . 'internationallyCalculator', $data);
	}

	public function senderDetails(){
		return view($this->theme . 'senderDetails');
	}

	public function shippingDetails(){
		return view($this->theme . 'shippingDetails');
	}


	public function faq()
	{
		$templateSection = ['faq'];
		$data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');

		$contentSection = ['faq'];
		$data['contentDetails'] = ContentDetails::select('id', 'content_id', 'description', 'created_at')
			->whereHas('content', function ($query) use ($contentSection) {
				return $query->whereIn('name', $contentSection);
			})
			->with(['content:id,name',
				'content.contentMedia' => function ($q) {
					$q->select(['content_id', 'description']);
				}])
			->get()->groupBy('content.name');

		$data['increment'] = 1;
		return view($this->theme . 'faq', $data);
	}

	public function blog()
	{
		$data['title'] = "Blog";
		$data['allBlogs'] = Blog::with(['details', 'category.details'])->where('status', 1)->latest()->paginate(2);
		$data['relatedBlogs']  = Blog::with(['details', 'category.details'])->where('status',1)->latest()->get();
		$data['blogCategory'] = BlogCategory::with('details')->withCount('blog')->where('status', 1)->latest()->get();
		return view($this->theme . 'blog', $data);
	}


	public function blogDetails($slug = null, $id)
	{
		$data['title'] = "Blog Details";
		$data['singleBlog']    = Blog::with('details', 'category.details')->where('status', 1)->findOrFail($id);
		$data['allBlogCategory'] = BlogCategory::with('details')->withCount('blog')->where('status', 1)->latest()->get();
		$data['relatedBlogs']  = Blog::with(['details', 'category.details'])->where('id','!=',$id)->where('blog_category_id', $data['singleBlog']->blog_category_id)->where('status', 1)->latest()->paginate(3);
		return view($this->theme . 'blogDetails', $data);

	}


	public function CategoryWiseBlog($slug = null, $id){
		$data['title'] = "Blog";
		$data['allBlogs'] = Blog::with(['details', 'category.details'])->where('blog_category_id', $id)->where('status', 1)->latest()->paginate(config('basic.paginate'));
		$data['blogCategory'] = BlogCategory::with('details')->withCount('blog')->where('status', 1)->latest()->get();
		$data['relatedBlogs']  = Blog::with(['details', 'category.details'])->latest()->take(5)->latest()->get();
		return view($this->theme . 'blog', $data);
	}


	public function blogSearch(Request $request){

		$data['title'] = "Blog";
		$search = $request->search;

		$data['blogCategory'] = BlogCategory::with('details')->withCount('blog')->where('status', 1)->latest()->get();
		$data['relatedBlogs']  = Blog::with(['details', 'category.details'])->where('status', 1)->latest()->take(3)->inRandomOrder()->get();

		$data['allBlogs'] = Blog::with('details','category.details')
			->whereHas('category.details', function ($qq) use ($search){
				$qq->where('name','Like', '%'.$search.'%');
			})
			->orWhereHas('details', function ($qq2) use ($search){
				$qq2->where('title','Like', '%'.$search.'%');
				$qq2->orWhere('author','Like', '%'.$search.'%');
				$qq2->orWhere('details','Like', '%'.$search.'%');
			})
			->where('status', 1)
			->latest()->paginate(3);

		return view($this->theme . 'blog', $data);

	}

	public function contact()
	{
		$templateSection = ['contact'];
		$data['templates'] = Template::with('media')->templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');
		$title = 'Contact Us';
		$contact = null;
		$data['templatesMedia'] = null;
		if (count($data['templates']) > 0) {
			$data['templatesMedia']  = $data['templates']['contact'][0]->media;
			$contact = optional($data['templates']['contact'][0])->description;
		}

		return view($this->theme . 'contact', $data, compact('title','contact'));
	}

	public function contactSend(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:50',
			'email' => 'required|email|max:91',
			'subject' => 'required|max:100',
			'message' => 'required|max:1000',
		]);
		$requestData = Purify::clean($request->except('_token', '_method'));

		$basic = (object)config('basic');
		$basicEmail = $basic->sender_email;

		$name = $requestData['name'];
		$email_from = $requestData['email'];
		$subject = $requestData['subject'];
		$message = $requestData['message'] . "<br>Regards<br>" . $name;
		$from = $email_from;

		$headers = "From: <$from> \r\n";
		$headers .= "Reply-To: <$from> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$to = $basicEmail;

		if (@mail($to, $subject, $message, $headers)) {
			// echo 'Your message has been sent.';
		} else {
			//echo 'There was a problem sending the email.';
		}

		return back()->with('success', 'Mail has been sent');
	}

	public function subscribe(Request $request)
	{
		$purifiedData = Purify::clean($request->all());
		$validationRules = [
			'email' => 'required|email|min:8|max:100|unique:subscribes',
		];
		$validate = Validator::make($purifiedData, $validationRules);
		if ($validate->fails()) {
			return back()->withErrors($validate)->withInput();
		}
		$purifiedData = (object)$purifiedData;

		$subscribe = new Subscribe();
		$subscribe->email = $purifiedData->email;
		$subscribe->save();

		return back()->with('success', 'Subscribed successfully');
	}

	public function getLink($getLink = null, $id)
	{
		$getData = Content::findOrFail($id);

		$contentSection = [$getData->name];
		$contentDetail = ContentDetails::select('id', 'content_id', 'description', 'created_at')
			->where('content_id', $getData->id)
			->whereHas('content', function ($query) use ($contentSection) {
				return $query->whereIn('name', $contentSection);
			})
			->with(['content:id,name',
				'content.contentMedia' => function ($q) {
					$q->select(['content_id', 'description']);
				}])
			->get()->groupBy('content.name');

		$title = @$contentDetail[$getData->name][0]->description->title;
		$description = @$contentDetail[$getData->name][0]->description->description;
		return view($this->theme . 'getLink', compact('contentDetail', 'title', 'description'));
	}

	public function getTemplate($template = null)
	{
		$contentDetail = Template::where('section_name', $template)->firstOrFail();
		$title = @$contentDetail->description->title;
		$description = @$contentDetail->description->description;
		return view($this->theme . 'getLink', compact('contentDetail', 'title', 'description'));
	}

	public function setLanguage($code)
	{
		$language = Language::where('short_name', $code)->first();

		if (!$language) $code = 'US';
		session()->put('lang', $code);
		session()->put('rtl', $language ? $language->rtl : 0);

		return redirect()->back();
	}

	public function coverageArea($type = null){
		$operatorCountry = basicControl()->operatorCountry;
		$data['allCountries'] = Country::where('id', '!=', $operatorCountry->id)->where('status', 1)->get();
		return view($this->theme . 'coverageArea', $data, compact('type'));
	}

	public function packagingCost(){
		$templateSection = ['packaging-cost'];
		$data['packages'] = Package::with('variant', 'variant.packingService')->where('status', 1)->get();
		$data['templates'] = Template::templateMedia()->whereIn('section_name', $templateSection)->get()->groupBy('section_name');
		return view($this->theme . 'packagingCost', $data);
	}

}
