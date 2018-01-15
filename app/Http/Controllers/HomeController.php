<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\employee;
use App\Candidate;
use Maatwebsite\Excel\Facades\Excel;
use Alert;
use Illuminate\Support\Facades\Cache;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $candidates =[];


        return view('home',['candidates' =>$candidates]);
    }

    public function fileUpload(Request $request){
        
         $this->validate($request, [
            'excelupload' => 'required', //|mimes:ods,xls,xlsx
        ]);

        //requesting file
        $file = $request->file('excelupload');
        $destinationPath = 'upload/company/earthsolution'; //Folder where we want to upload the file (inside public folder)
        $filename = $file->getClientOriginalName(); //filename original
        $filename = rand(0, 999999999).preg_replace('/\s+/', '', $filename);
        $upload_success = $request->file('excelupload')->move($destinationPath, $filename);
        $path = $destinationPath.'/'.$filename;
        //maat excel file uploading
        $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader) {
            $reader->formatDates(true, 'F d, Y');
            $reader->take(100);
        })->get();
        $request->session()->put('filepath', $path);
        
        //put data into array

        $m = $e = collect($data);

        $previous = Candidate::all(); //previous data
        $candidates = array();
       
        $temp = [[]];
        $original = [];
        $pool_email = [];
        $pool_mobile = [];
        $originals = [];
        $duplicates = [];
        $mobile_exist_arr = [];
        $duplicates = [];
        $nonduplicates = [];

         //remove empty mobile
        $filteredmobile = $m->filter(function ($value, $key) {
            return $value->contact != '';
        });
        $m = $filteredmobile;

        // remove empty email
        $filteredname = $e->filter(function ($value, $key) {
            return $value->name != '';
        });
        $e = $filteredname;

        $unique_mobile = $m->unique('contact');
        $dup_mobile = $m->diff($unique_mobile); //duplicate mobile in excel

        foreach ($data as $key => $value) {
           $current['name'] = $value->name;
           $current['contact'] = preg_replace('/[^0-9]/', '', $value->contact);
           

           foreach ($previous as $pool_key => $pool_value) {
                
                if ($pool_value->contact != '') {
                    array_push($pool_mobile, $pool_value->contact);
                }
                array_push($originals, $previous[$pool_key]);
            }
            $dup = [];
            $mobile_exist = array_search($value->contact, $pool_mobile);
            
            if ($mobile_exist !== false) { //false means no duplicate
                
                array_push($duplicates, $data[$key]); //duplicated records
                $dup['key'] = $key;
            }
            else {
                 //non duplicate data
                 $dup['key'] = $key;
                 $ckey = $dup['key'];
                 array_push($nonduplicates, $data[$ckey]);
            }

            
            //push array into other array
            array_push($candidates, $current);

        }
        //dd($duplicates,$nonduplicates);
        
        return view('excel-preview', ['candidates' => $candidates,'dup_mobile' => $dup_mobile,'duplicates' => $duplicates,'nonduplicates' => $nonduplicates]);        
        

    }


    public function saveExcel(Request $request){

        $filepath = $request->session()->get('filepath');
        $candidates = json_decode($request->candidates, true);
        
        foreach ($candidates as $key => $candidate) {
             
             $candidates[$key]['created_at'] = date('Y-m-d H:i:s');
        }
        $candidates = array_values($candidates);
        $result = Candidate::insert($candidates);
        Alert::success('Candidate Saved', 'Great!');
        return redirect(route('home'));
    
    }

  public function searchCandidate(Request $request){

        $this->validate($request, [
            'searchcandidate' => 'required', //|mimes:ods,xls,xlsx
        ]);

        $keywords = explode(',', $request->searchcandidate);

        $value = Cache::remember('candidate', 'name', function () {
                return DB::table('candidate')->get();
         });
        
        $query = Candidate::query();

        //Search All Keywords
        // if (trim($request->keywords) != '') {
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $key => $keyword) {
                    $q->orWhere('candidate.name', 'like', '%'.trim($keyword).'%');
                    //$q->orWhere('candidate.name', '=', $keyword);
                }
            });
        // }
        
        //$query->orWhere('candidate.name', '=', $keywords);
        $query->select('candidate.*');
        $query->orderBy('candidate.created_at', 'ASC');

        $candidates = $query->get();
        //dd($candidates);
        if(count($candidates) == 0){
          Alert::info('No Record Found', 'Oops!');
          return redirect(route('home'));
        }
        else{
          return view('home',['candidates' => $candidates]);
          //return redirect(route('home', ['candidates' => $candidates]));
          
        }

  }



}
