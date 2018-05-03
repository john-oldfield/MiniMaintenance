<style>

h1
{
    font-family: Arial, Helvetica, sans-serif;
    text-align: center;
}

table
{
    font-family: Arial, Helvetica, sans-serif;
    width: 100%;
    border: 1px solid black;
    margin-bottom: 20px;
}

td
{
    padding: 10px;
}


img{
    max-width: 300px;
}

.page-break
{
    page-break-after: always;
}

.bold-text
{
    font-weight: bold;
}
</style>

@if(count($history) > 0)
    <h1>Service History</h1>
    <table>
        <tr>
        <td><span class="bold-text">Make: </span> {{$user->Car->Make->name}}</td>
            <td><span class="bold-text">Model: </span>{{$user->Car->Model->name}}</td>
            <td><span class="bold-text">Engine: </span>{{$user->Car->Engine->cc}}cc</td>
            <td><span class="bold-text">Mileage: </span>{{number_format($user->Car->mileage)}}</td>
        </tr>
    </table>
        @foreach($history as $hist)
        <table>
        <tr>
            <td>
                <p class="bold-text">DATE COMPLETED</p>
                <p>{{$hist->created_at->format('d/m/Y')}}</p>
            </td>
            <td colspan="2">
                <p class="bold-text">COMPELTED BY</p>
                <p>{{$hist->completedBy}}</p>
            </td>
            <td>
                <p class="bold-text">AMOUNT</p>
                <p>£{{number_format($hist->amount, 2)}}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p class="bold-text">DESCRIPTION</p>
                <p>{{$hist->task}}</p>
            </td>
            <td colspan="2">
                <p class="bold-text">NOTES</p>
                @if($hist->descrip == "" || null)
                    <p>None.</p>
                @else
                    <p>{{$hist->descrip}}</p>
                @endif
            </td>
        </tr>
                @if(!empty($hist->receipt))
                <tr>
                        <td colspan="4">
                            <p class="bold-text">RECEIPT</p>
                    <img src="{!! public_path().'/storage/receipts/'.$hist->receipt !!}" class="img-fluid"/>
                </td>
            </tr>
        </table>
        <div class="page-break"></div>
        @else
        </table>
                @endif
        @endforeach
@endif