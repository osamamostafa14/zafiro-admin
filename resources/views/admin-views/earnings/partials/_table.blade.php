@foreach($delivery_men as $key=>$dm)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>
                                        <span class="d-block font-size-sm text-body">
                                            {{$dm['full_name']}}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="height: 60px; width: 60px; overflow-x: hidden;overflow-y: hidden">
                                            <img width="60" style="border-radius: 50%"
                                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                 src="{{asset('storage/app/public/profile')}}/{{$dm['image']}}">
                                        </div>
                                     
                                    </td>
                                    <td>
                                        {{$dm['phone']}}
                                    </td>
                                    <td>
                                        <!-- Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <i class="tio-settings"></i>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                                   href="{{route('admin.driver.view', [$dm['id']])}}">View</a>
                                               
                                               
                                            </div>
                                        </div>
                                        <!-- End Dropdown -->
                                    </td>
                                </tr>
                            @endforeach