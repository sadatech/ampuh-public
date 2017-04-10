<style type="text/css">
    .panel {
        border: 1px solid #DDDDDD;
        margin: 30px auto;
        width: 70%;
        padding: 0px;
        border-radius: 10px 10px 0 0;
    }

    .panel-primary {
        background: #0080FF;
    }

    .panel-heading {
        color: #fff;
        padding: 15px;
        font-weight: bold;
    }

    .panel-body {
        padding: 10px;
        background: #fff;
        color: #000;
    }

    .img {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-top: 10px;
    }
</style>
<div class="panel panel-primary">
    <div class="panel-heading"><h4>Loreal Open Ticket</h4></div>
    <div class="panel-body">
       {{--{{dd($ticketMessage)}}--}}
        {{$ticketMessage['message']}}<br>
        @if($ticketMessage['attachment'] != '')
            <img src="http://ampuh.dev/attachment/support/{{$ticketMessage['attachment']}}" width="300px" height="300px"
                 class="img"/>
        @endif
        <br>
        <span style="color: blue;">
            Lihat Detail:
            <a href="{{url($ticketMessage['url'])}}">Detail Message</a>
        </span>
    </div>
</div>
