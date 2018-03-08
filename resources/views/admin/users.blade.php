@extends('layouts.admin')

@section('content')
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          I Do App Users
          <small>8th September 2017</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Users</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">

          <div class="row">
            <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">User Statistics</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Users Group</th>
                      <th>Percentages</th>
                      <th style="width: 40px">Label</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td>Male</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-red">55%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td>Female</td>
                      <td>
                        <div class="progress progress-xs">
                          <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-yellow">70%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td>With Engagements</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-light-blue">30%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td>With Weddings</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-green">90%</span></td>
                    </tr>
                  </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix"></div>
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->

            <div class="col-md-6">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title">Filter Users</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <div class="row">

                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Gender</label>
                            <select class="form-control select2" style="width: 100%;">
                              <option selected="selected">Any</option>
                              <option>Male</option>
                              <option>Female</option>
                            </select>
                          </div>
                          <!-- /.form-group -->
                          <div class="form-group">
                            <label>Status</label>
                            <select class="form-control select2" style="width: 100%;">
                              <option selected="selected">Any</option>
                              <option>Pending</option>
                              <option>Approved</option>
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4">
                            <div class="form-group">
                              <label>Joined Through</label>
                              <select class="form-control select2" style="width: 100%;">
                                <option selected="selected">Any</option>
                                <option>Email</option>
                                <option>Facebook</option>
                              </select>
                            </div>
                          <!-- /.form-group -->
                          <div class="form-group">
                            <label>Marital Status</label>
                            <select class="form-control select2" style="width: 100%;">
                              <option selected="selected">Any</option>
                              <option>None</option>
                              <option>Wedded</option>
                              <option>Engaged</option>
                            </select>
                          </div>
                          <!-- /.form-group -->
                        </div>
                        <!-- /.col -->

                        <!-- /.col -->
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control">
                          </div>
                          <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control">
                          </div>

                          <button type="submit" class="btn btn-primary pull-right">Filter Users</button>
                        </div>
                    </div>
                    <!-- /.row -->
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer clearfix">
                  </div>
                  <!-- /.box-footer -->
                </div>
                <!-- /.box -->
            </div>
          </div>
          <!-- /.row -->

          <div class="row">


          </div>

        <div class="row">
          <div class="col-xs-12">
            <div class="box" style="margin-bottom: 300px;">
              <div class="box-header">
                <h3 class="box-title">Registered Users</h3>

              </div>
              <!-- /.box-header -->
              <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                  <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Gender</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Joined Through</th>
                    <th>Email</th>
                    <th>Marital Status</th>
                  </tr>
                  <tr>
                    <td>183</td>
                    <td><a href="{{ route('admin.single-user', ['user' => '1']) }}">John Doe</a></td>
                    <td>Male</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-success">Approved</span></td>
                    <td><i class="fa fa-facebook"></i> &nbsp;Facebook</td>
                    <td>N/A</td>
                    <td>Wedded</td>
                  </tr>
                  <tr>
                    <td>219</td>
                    <td>Alexander Pierce</td>
                    <td>Female</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-warning">Pending</span></td>
                    <td><i class="fa fa-envelope"></i> &nbsp;Email</td>
                    <td>findingedward@gmail.com</td>
                    <td>None</td>
                  </tr>
                  <tr>
                    <td>657</td>
                    <td>Bob Doe</td>
                    <td>Male</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-danger">Denied</span></td>
                    <td><i class="fa fa-facebook"></i> &nbsp;Facebook</td>
                    <td>N/A</td>
                    <td>None</td>
                  </tr>
                  <tr>
                    <td>175</td>
                    <td>Mike Doe</td>
                    <td>Male</td>
                    <td>11-7-2014</td>
                    <td><span class="label label-danger">Denied</span></td>
                    <td><i class="fa fa-facebook"></i> &nbsp;Facebook</td>
                    <td>N/A</td>
                    <td>Engaged</td>
                  </tr>
                </table>
              </div>
              <!-- /.box-body -->
              <!-- /.box-footer -->
            </div>
            <!-- /.box -->
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
@endsection

@section('scripts')
{{-- <script src="{{ asset('js/select2.full.min.js') }}"></script>
<script>
  $(function () {
    $(".select2").select2();
  });
</script> --}}

@endsection
