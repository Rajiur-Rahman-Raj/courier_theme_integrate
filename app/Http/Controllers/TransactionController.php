<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth']);
		$this->middleware(function ($request, $next) {
			$this->user = auth()->user();
			return $next($request);
		});
		$this->theme = template();
	}

	public function index()
	{
		$user = Auth::user();

		$transactions = Transaction::with('transactional')->where('user_id', $user->id)->latest()->paginate(config('basic.paginate'));
		return view($this->theme . 'user.transaction.index', compact('transactions'));
	}

	public function search(Request $request)
	{
		$search = $request->all();
		$fromDate = Carbon::parse($request->from_date);
		$toDate = Carbon::parse($request->to_date)->addDay();

		$transaction = Transaction::where('user_id', $this->user->id)->with('transactional')
			->when(@$search['transaction_id'], function ($query) use ($search) {
				return $query->where('trx_id', 'LIKE', "%{$search['transaction_id']}%");
			})
			->when(@$search['remark'], function ($query) use ($search) {
				return $query->where('remarks', 'LIKE', "%{$search['remark']}%");
			})
			->when(isset($search['from_date']), function ($q2) use ($fromDate) {
				return $q2->whereDate('created_at', '>=', $fromDate);
			})
			->when(isset($search['to_date']), function ($q2) use ($fromDate, $toDate) {
				return $q2->whereBetween('created_at', [$fromDate, $toDate]);
			})
			->paginate(config('basic.paginate'));
		$transactions = $transaction->appends($search);

		return view($this->theme . 'user.transaction.index', compact('transactions'));
	}
}
