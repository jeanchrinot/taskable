@extends('layouts.app')

@section('content')
        
        @include('includes.sidebar')
        <section class="content">
            <div class="content__header">
                <h1 class="content__title">My tasks</h1>
            </div>
            <div class="content__main">
                <div class="row">
                    <div class="col-12">
                      <div class="card">
                          <div class="card-header">
                            <div class="row">
                              <div class="form-group col-md-4 filter-form">
                                <label for="status">Filter by status: </label>
                                <select class="form-control form-control-sm filter" name="status" id="status">
                                  <option value="all">All</option>
                                  <option value="complete">Complete</option>
                                  <option value="in_progress">In Progress</option>
                                  <option value="not_started">Not Started</option>
                                </select>
                              </div>
                              <div class="form-group col-md-4 filter-form">
                                <label for="priority">Filter by Priority: </label>
                                <select class="form-control form-control-sm filter" name="priority" id="priority">
                                  <option value="all">All</option>
                                  <option value="high">High</option>
                                  <option value="medium">Medium</option>
                                  <option value="low">Low</option>
                                </select>
                              </div>
                              <div class="form-group col-md-4 filter-form">
                                <label for="search" class="search-label">Search :</label>
                                
                                  <input type="text" class="form-control" id="search" placeholder="Seache here...">
                                
                              </div>
                            </div>
                          </div>
                          <div class="card-body">
                        <table class="table table-sm table-responsive-sm white-bg">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Task</th>
                              @if(auth()->check() && auth()->user()->hasRole('admin'))<th scope="col">User</th>@endif
                              <th scope="col">Priority</th>
                              <th scope="col">Status</th>
                              <th scope="col">Start Date</th>
                              <th scope="col">End Date</th>
                              <th scope="col">Complete</th>
                              <th scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if(count($tasks)>0)
                            @foreach($tasks as $key=>$task)
                            @php
                              $status = taskStatus($task->id);
                              $progress = taskProgress($task->id);
                              $priority = taskPriority($task->id);
                            @endphp
                            <tr>
                              <th scope="row">{{ $key+1 }}</th>
                              <td>{{ $task->name }}</td>
                              @if(auth()->check() && auth()->user()->hasRole('admin'))<td>Mark</td>@endif
                              <td><span class="badge badge-{{ $priority[2] }}">{{ $priority[1] }}</span></td>
                              <td><span class="badge badge-{{ $status[2] }}">{{ $status[1] }}</span></td>
                              <td>{{ date_format(date_create($task->start_date),'d/m/Y') }}</td>
                              <td>05/09/2020</td>
                              <td>{{ $progress[0].'/'.$progress[1] }}</td>
                              <td><span role="button" data-toggle="modal" data-target="#listDetailsModal"><i class="fa fa-eye" role="button" data-toggle="tooltip" data-placement="top" title="View"></i></span> 
                              
                                  <span role="button" data-toggle="modal" data-target="#deleteListModal"><i class="fa fa-trash" role="button" data-toggle="tooltip" data-placement="top" title="Delete"></i></span></td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                              <td colspan="7">No task found.</td>
                            </tr>
                            @endif
                          </tbody>
                          <tfoot>
                            <tr>
                                <td colspan="7">
                                    <!-- <nav aria-label="Page navigation example">
                                      <ul class="pagination pagination-sm">
                                        <li class="page-item">
                                          <a class="page-link" href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                            <span class="sr-only">Previous</span>
                                          </a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                          <a class="page-link" href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                          </a>
                                        </li>
                                      </ul>
                                    </nav> -->
                                    {{ $tasks->appends(request()->input())->links() }}
                                </td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <div class="modal fade" id="listDetailsModal" tabindex="-1" role="dialog" aria-labelledby="listDetailsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog__large" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="listDetailsModalLabel">To-do list Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <div class="row">
                    <div class="col-md-6 no-padding">
                        <div class="card">
                          <div class="card-header">
                            <i class="fa fa-align-left"></i> Items in the list
                          </div>
                          <div class="card-body">
                            <div class="input-group mb-3">
                              <input type="text" class="form-control" placeholder="Add item to list..." aria-label="Add item to list" aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button"><i class="fa fa-plus"></i> Add</button>
                              </div>
                            </div>
                            <ul class="todo-list text-left">
                                <li role="button"><span class="list-style"></span> <input type="checkbox" checked="checked"> <span class="todo-list__text" style="text-decoration: line-through;font-style: italic;">Shopping list item for monday</span></li>
                                <li><span class="list-style"></span> <input type="checkbox" checked="checked"> Shopping list item for monday</li>
                                <li><span class="list-style"></span> <input type="checkbox"> Shopping list item for monday</li>
                                <li><span class="list-style"></span> <input type="checkbox"> Shopping list item for monday</li>
                                <li><span class="list-style"></span> <input type="checkbox" checked="checked"> Laundry Detergent 1</li>
                                <li><span class="list-style"></span> <input type="checkbox" checked="checked"> Sugar 1 kg</li>
                                <li><span class="list-style"></span> <input type="checkbox" checked="checked"> Shopping list item for monday</li>
                                <li><span class="list-style"></span> <input type="checkbox" checked="checked"> Shopping list item for monday</li>
                                <li><span class="list-style"></span> <input type="checkbox"> Shopping list item for monday</li>
                                <li><span class="list-style"></span> <input type="checkbox"> Shopping list item for monday</li>
                                <li><span class="list-style"></span> <input type="checkbox" checked="checked"> Laundry Detergent 1</li>
                                <li><span class="list-style"></span> <input type="checkbox" checked="checked"> Sugar 1 kg</li>
                            </ul>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-6 no-padding">
                        <div class="card">
                          <div class="card-header">
                            List Title : My shopping list of monday
                          </div>
                          <div class="card-body">
                              <table class="table table-sm table-striped">
                                <thead>
                                  <tr>
                                    <th scope="col">Properties</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Edit</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Title</td>
                                    <td>My shopping list of monday</td>
                                    <td><i class="fa fa-pencil" role="button" data-toggle="tooltip" data-placement="top" title="Edit"></i></td>
                                  </tr>
                                  <tr>
                                    <td>Priority</td>
                                    <td><span class="badge badge-info">Normal</span></td>
                                    <td><i class="fa fa-pencil" role="button" data-toggle="tooltip" data-placement="top" title="Edit"></i></td>
                                  </tr>
                                  <tr>
                                    <td>User</td>
                                    <td>Thornton</td>
                                  </tr>
                                  <tr>
                                    <td>Status</td>
                                    <td><span class="badge badge-danger">Canceled</span></td>
                                  </tr>
                                  <tr>
                                    <td>Start Date</td>
                                    <td>08/05/2020</td>
                                  </tr>
                                  <tr>
                                    <td>End Date</td>
                                    <td>09/05/2020</td>
                                  </tr>
                                  <tr>
                                    <td>Complete</td>
                                    <td>12/20</td>
                                  </tr>
                                </tbody>
                              </table>
                          </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteListModal" tabindex="-1" role="dialog" aria-labelledby="deleteListModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteListModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                Are you sure to delete this list?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Delete</button>
              </div>
            </div>
          </div>
        </div>

@endsection