@extends('layouts/main')

@section('content')

	<table class="table">
				<thead>
					<tr>
						<th></th>
						<th>Nom</th>
						<th>Rôle</th>
						<th></th>
					</tr>
				</thead>
				<tbody>

         <!--  je bouble sur les posts et je les affiches en vérifiant les droits définis dans ArticlesPolicy--> 
					<?php $i=0 ?>
@foreach ( $users as $user )
<tr>

	<td><?php echo ++$i; ?></td>

  <td><a href="/articles/{{ $user->id }}">{{ $user->name }}</a></td>

@can('administrer', $user)
    <td style="color:green">administrateur</td>
    <td> <a style="color:red" href=""><input type="submit" value="Supprimer" /></a></td>

@elsecannot('administrer',$user)
<td style="color:red">utilisateur</td>
<td> <a href="/admin/users/{{$user->id}}/delete"><input type="submit" value="Supprimer" /></a></td>

 @endcan
 
 <td> <a href="/admin/users/{{$user->id}}/role"><input type="submit" value="Faire Administrer" /></a></td>

</tr>
@endforeach
</tbody>
</table>


@endsection
