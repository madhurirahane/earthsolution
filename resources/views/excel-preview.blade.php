@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <section class="content-header">
                      <h3 class="ui blue header"><i class="ui users icon"></i>UPLOAD CANDIDATES</h3>
                    </section>
                </div>
                <ol class="breadcrumb">
                    <li><a href="{{route('home')}}"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a></li>
                    <li class="active">Excel Upload</li>
                </ol>
                
                <div class="panel-body">
                    <section class="content">
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="box-body">
                                    @if(count($dup_mobile) !=0)
                                    <div class="ui divider"></div>
                                    <h5 class="ui header red left floated" id="dup_mobile">Duplicate Found In Excel</h5><br>
                                     <div class="alert alert-warning">
                                      <strong>Warning!</strong> It Seems below records duplicated in excel,remove it from excel then upload it again.
                                    </div>
                                    <table class="ui red very compact table">
                                        <thead>
                                            <tr class="center aligned">
                                                <th>ID</th>
                                                <th>NAME</th>
                                                <th>MOBILE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dup_mobile as $key => $value)
                                            <tr class="center aligned danger">
                                                <td>{{ $key+1 }}</td>
                                                <td class="error"><strong>{{ $value->name}}</strong></td>
                                                    <td class="error" data-toggle="tooltip" data-placement="right" title="Mobile is duplicated!"><i class="attention icon"></i> <strong>{{ $value->contact }}</strong></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                               </div>
                            </div>
                            
                            @if(count($duplicates) !=0)
                                    <div class="ui divider"></div>
                                    <h5 class="ui header red left floated" id="dup_mobile">Duplicate Found In Database</h5><br>
                                    <div class="alert alert-warning">
                                      <strong>Warning!</strong> It Seems below records already exist,remove it from excel then upload.
                                    </div>
                                    <table class="ui red very compact table">
                                        <thead>
                                            <tr class="center aligned">
                                                <th>ID</th>
                                                <th>NAME</th>
                                                <th>MOBILE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($duplicates as $key => $value)
                                            <tr class="center aligned danger">
                                                <td>{{ $key+1 }}</td>
                                                <td class="error"><strong>{{ $value->name}}</strong></td>
                                                    <td class="error" data-toggle="tooltip" data-placement="right" title="Mobile is duplicated!"><i class="attention icon"></i> <strong>{{ $value->contact }}</strong></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <hr><br>
                                    @endif

                             @if(count($candidates) AND count($dup_mobile) ==0 AND count($duplicates) ==0)
                                    <div class="ui divider"></div>
                                    <h5 class="ui header red left floated" id="dup_mobile">Excel</h5><br>
                                    <table class="ui red very compact table">
                                        <thead>
                                            <tr class="center aligned">
                                                <th>ID</th>
                                                <th>NAME</th>
                                                <th>MOBILE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($candidates as $key => $candidate)
                                            <tr class="center aligned">
                                                <td>{{ $key+1 }}</td>
                                                <td>{{$candidate['name']}}</td>
                                                <td>{{$candidate['contact']}}</td>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                        </div>
                    </section>
                     @if(count($dup_mobile) ==0 AND count($duplicates) ==0)
                     <form class="ui form" method="POST" action="{{route('save-excel')}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="candidates" value="{{json_encode($candidates)}}">
                            <button type="submit" name="submit" class="btn btn-success">Success</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
