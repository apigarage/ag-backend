<table width="100%" bgcolor="#484e50" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="preheader" ><!--full text-->
  <tbody>
    <tr>
      <td align="center">
        <table width="800" bgcolor="#fefbf7" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
          <tbody>
            <tr>
              <td align="center">
                <table width="600"  cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                  <tbody>
                    <tr><!--spacing-->
                      <td width="100%" height="65"></td>
                    </tr><!--end spacing-->
                    <tr><!--full text title-->
                      <td align="center">
                        <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidthinner">
                          <tbody>
                            <tr>
                              <td style="font-family: 'Oxygen',  Helvetica, arial, sans-serif; font-size: 26px; color: #55626c; text-align:left; line-height: 36px;  text-transform:uppercase;">
                                <p style="font-weight:700; line-height: 36px;">{{$params['title']}}</p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr><!--end full text title-->
                    <tr><!--spacing-->
                      <td width="100%" height="35"></td>
                    </tr><!--end spacing-->
                    <tr><!--full text content-->
                      <td align="center">
                        <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidthinner">
                          <tbody>
                            <tr>
                              <td td style="font-family: 'Oxygen', Helvetica, arial, sans-serif; font-size: 14px; color: #95a5a6; text-align:left; line-height: 28px;">
                                <p style="margin-bottom:40px !important; line-height: 28px;">
                                  @if (empty($params['user']['name']))
                                    {{$params['user']['email'] }}
                                  @else
                                    {{$params['user']['name'] }}
                                  @endif
                                  has invited you to be part of the project -- {{ $params['project']['name'] }} on API Garage. You can get started by downloading
                                  the latest version at <a href="http://apigarage.com/download.html"> http://apigarage.com/download.html </a>.
                                </p>
                                <p>
                                  After signing up, you will be able to:
                                  <ul>
                                    <li>Read access to all current project documentation</li>
                                    <li>Write access to add to the documentation</li>
                                    <li>Stay up to date with project updates</li>
                                    <li>Create you own environments to test projects with.</li>
                                    <li>Test End points with just one click!</li>
                                  </ul>
                                </p>
                                <p>
                                  Thanks,<br/>

                                  API Garage Team.<br/>
                                </p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
        <!--end full text-->
      </td>
    </tr>
  </tbody>
</table>
