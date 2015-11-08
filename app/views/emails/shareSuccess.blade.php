  <div style="background-color:#ffffff;border-color:#d6d6d6;border-width:1px;border-style:solid;padding:0 8px">
    <p>
      Project Shared: <span style="font-weight:bold">{{ $params['project']->name }}</span>
    </p>
  </div>
  <div style="background-color:#f9f9f9;border-color:#d6d6d6;border-width:0 1px 1px;border-style:solid;padding:7px 8px">
    <p>
        <b>{{ $params['user']->name }}(<a href="mailto:{{ $params['user']->email }}">{{ $params['user']->email }}</a>)</b>
        invites to collaborate on the project <b>{{ $params['project']->name }}</b> on API Garage.
    </p>
  </div>
