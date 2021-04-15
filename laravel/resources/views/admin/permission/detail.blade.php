<div class="table-responsive">
	<table class="table table-bordered">
        <thead class="thead-dark text-center">
            <tr>
                <th colspan="12">Foto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center" colspan="12">
                    <img class="card-img-top img-fluid" src="{{ url('photo/user_permission/'.$target->photo.'') }}" style="display: block; margin: 0 auto; max-width:50%; max-height:50%;">
                </td>
            </tr>
        </tbody>
        <thead class="thead-dark text-center">
            <tr>
                <th colspan="12">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center" colspan="12">
                    {!! nl2br($target->description) !!}
                </td>
            </tr>
        </tbody>
	</table>
</div>