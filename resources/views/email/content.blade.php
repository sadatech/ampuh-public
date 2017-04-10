<!DOCTYPE html>
<html>
<head>
  <style type="text/css">
  table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    border: 1px solid #ddd;
  }

  th, td {
    border: none;
    text-align: left;
    padding: 8px;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  </style>
  <title></title>
</head>
<body>
  <div style="width:100%;height: 900px;background: #336e7b;font-family: sans-serif;" align="center">
    <div align="center">
      <span style="color: #fff;font-family: sans-serif;">
        <b>Loreal Apps</b>
      </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <span style="color: #000;font-family: sans-serif;">
        <b>To Website</b>
      </span>
      <br>
      <br>
    </div>
    <div style="width:95%;height: 800px;background: #fff;">
      <div style="padding: 10px;">
        Perihal :
        <span><b>
          Email Invitation for Loreal Apps</b>
        </span>
        <hr></hr>
      </div>
      <div style="padding-left: 30px;" align="left">
        <h3>
          <p style="color: #2ecc71 ;">Kepada Bpk/Ibu <b>Rizal</b></p>
        </h3>
        <h4>
          <p>berikut ini kami Kirimkan link invitation email untuk loreal Apps</p>
        </h4>

        <h3>
          <p>Silahkan klik link berikut</p>
          <a href="{{url('users/invitation/' . $token)}}">{{url('users/invitation/' . $token)}}</a>
        </h3>
    </div>
  </div>
</body>
</html>