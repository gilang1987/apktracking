<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Outlets;
use App\Models\Inout;
use Response;

class InoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(PermissionDataTable $dataTable) {
        $components['breadcrumb'] = (object) [
			'first' => 'Riwayat',
			'second' => 'Check In/Out'
		];
        $components['outlets'] = Inout::where('id_user', Auth::user()->id)->selectRaw('DATE(created_at) AS created_at')->distinct()->latest('created_at')->get();
        return $dataTable->render('user.permission.list', $components);
    }

    /*public function index()
    {
        $res = CheckInOut::where('id_user',Auth::user()->id)->whereNull('check_out')->get();

        return view('inout.index',compact('res'));
    }*/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('inout.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();//dd($input['image']);

        $fltype = explode("image/", explode(";base64,", $input['image'])[0])[1];
        $fl_base64 = base64_decode(explode(";base64,", $input['image'])[1]);
        $fn = date("U");
        Storage::disk('public')->put($fn . "." . $fltype,$fl_base64);
        

        CheckInOut::create([
            'id_user' => Auth::user()->id,
            'id_outlet' => $input['outlet_id'],
            'check_in' => date("Y-m-d H:i:s"),
            'latitude' => $input['latitude'],
            'longitude' => $input['longitude'],
            'fn_checkin' => $fn . "." . $fltype,
            'note_checkin' => $input['note_checkin']
        ]);

        return view('inout.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getOutlets(Request $req) {
        //dd($req->all('term'));

        $term = $req->all('term');
        //dd($term['term']);
        //$term = '';

        $results = array();

        // this will query the users table matching the first name or last name.
        // modify this accordingly as per your requirements
        //dd(Auth::user());
        
        $queries = Outlets::where('deskripsi','LIKE',"%".$term['term']."%")->get();
        

        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->id, 'value' => $query->deskripsi ." (". $query->alamat .")" ];
        }
        return Response::json($results)->withCallback();
    }
}
