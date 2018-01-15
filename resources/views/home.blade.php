@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                     <form action="/fileupload" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                        <div class="field form-group {{ $errors->has('excelupload') ? ' has-error' : '' }}">
                          <label for="imgUpload1">upload excel</label>
                          <input type="file" id="excelupload" name="excelupload">
                          @if ($errors->has('excelupload'))
                                <span class="help-block">
                                <strong>{{ $errors->first('excelupload') }}</strong>
                                </span>
                          @endif

                      </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form> 
                    
                </div>
            </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-default">
                <div class="panel-heading">Search Candidate</div>
                <div class="panel-body">
                     <form action="{{route('search-candidate')}}" method="POST" enctype="multipart/form-data">
                      {{ csrf_field() }}
                        <div class="field form-group {{ $errors->has('searchcandidate') ? ' has-error' : '' }}">
                          <label for="imgUpload1">Search By Name</label>
                          <input type="text" class="form-control" id="searchcandidate" name="searchcandidate">
                          @if ($errors->has('searchcandidate'))
                                <span class="help-block">
                                <strong>{{ $errors->first('searchcandidate') }}</strong>
                                </span>
                          @endif
                      </div>
                        <button type="submit" class="btn btn-success">Search</button>
                    </form> 
                    <hr><br>
                    
                    @if(count($candidates)!=0)
                      <table class="table">
                        <thead>
                            <tr class="center aligned">
                                <th>NAME</th>
                                <th>MOBILE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($candidates as $key => $candidate)
                            <tr class="">
                                <td>{{$candidate['name']}}</td>
                                <td>{{$candidate['contact']}}</td>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
