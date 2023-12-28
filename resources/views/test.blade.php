@foreach($response as $data)
<p>{{$data->manage_id}}</p>
<p>{{$data->user_id}}</p>
<p>{{$data->user_name}}</p>
<p>{{$data->handover_passhash}}</p>
<p>{{$data->has_weapon_exp_point}}</p>
<p>{{$data->user_rank}}</p>
<p>{{$data->login_days}}</p>
<p>{{$data->max_stamina}}</p>
<p>{{$data->last_stamina}}</p>
    <!-- <p>{{$data}}</p> -->
@endforeach