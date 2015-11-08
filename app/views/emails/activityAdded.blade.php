  <div style="background-color:#ffffff;border-color:#d6d6d6;border-width:1px;border-style:solid;padding:0 8px">
    <p>
      <span style="color:#0000b2;font-weight:bold">{{$params['title']}}</span>
    </p>
  </div>
  <div style="background-color:#f9f9f9;border-color:#d6d6d6;border-width:0 1px 1px;border-style:solid;padding:7px 8px">
    <p>
      @if(!empty($params['activity']->description))
      {{$params['activity']->description}}
      @endif
    </p>
  </div>
