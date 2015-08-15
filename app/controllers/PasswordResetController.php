<?php

class PasswordResetController extends Controller {

  /**
   * Send an email with code
   *
   * @return Response
   */
  public function send_reset_code()
  {
    $input = Input::all();

    // Validate Input
    $validator = Validator::make($input, [
      'email' => 'required|email|exists:users',
    ], [
      'exists' => 'Email does not exist in the system.'
    ]);
    if ($validator->fails())
    {
      return Response::json( $validator->messages() , 401);
    }

    // Persist Token
    $email = $input['email'];
    $user = User::where('email', '=', $email)->first();
    $token = substr(md5(uniqid(mt_rand(), true)), 0, 8);
    $resetToken = ResetToken::create([
      'email' => $email,
      'token' => $token,
      'used' => false
    ]);

    // Send email to the user
    Mail::send('emails.passwordReset', ['token' => $token], function($message) use($email, $token)
    {
       $message->to($email)->subject('Password Code - ' . $token);
    });

    // Return 200-OK
    return Response::json();
  }

  /**
   * verify the code
   * Theoritically, we can remove this function because reset_password() function
   * is also validating the password. However, this function validates the code
   * by itself and prompts user on the "verify code" screen. All in all, it's
   * better user experience.
   *
   * @return Response
   */
  public function verify_code()
  {
    $input = Input::all();
    // Validate Input
    $validator = Validator::make($input, [
      'email' => 'required',
      'token' => 'required'
    ]);
    if ($validator->fails())
    {
      return Response::json( $validator->messages() , 401);
    }

    // Verify Code
    $token = $input['token'];
    $email = $input['email'];
    $resetToken = ResetToken::where('email', '=', $email)
      ->where('token', '=', $token)
      ->where('used', '=', FALSE)
      ->first();

    // If code and email are associated, return 200.
    if($resetToken)
    {
      // Mark all other codes as used, exceept current one.
      ResetToken::where('email', '=', $email)
        ->where('token', '!=', $token)
        ->update(array('used' => true));
      return Response::json();
    }

    // If code and email are not associated, return 401 with error message.
    return Response::json([
      'token' => ['Invalid Verification Code']
    ], 401);
  }

  /**
   *  Reset the password for the user
   *
   * @return Response
   */
  public function reset_password()
  {
    $input = Input::all();
    // Validate Input
    $validator = Validator::make($input, [
      'email' => 'required',
      'token' => 'required',
      'password' => 'required|min:8'
    ]);
    if ($validator->fails())
    {
      return Response::json( $validator->messages() , 401);
    }

    // Verify Code
    $token = $input['token'];
    $email = $input['email'];
    $resetToken = ResetToken::where('email', '=', $email)
      ->where('token', '=', $token)
      ->where('used', '=', FALSE)
      ->first();

    // If code and email are associated,
    if($resetToken)
    {
      // Get the user and update the password
      $user = User::where('email', '=', $email)->first();
      $password = $input['password'];
      $user->password = $password;
      $user->save();

      // Mark all other codes as used
      ResetToken::where('email', '=', $email)
        ->update(array('used' => true));

      return Response::json(); // return 200-OK
    }

    // If code and email are not associated, return 401 with error message.
    return Response::json([
      'token' => ['Invalid Verification Code']
    ], 401);
  }

}
