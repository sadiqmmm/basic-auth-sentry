<?php

use basicAuth\formValidation\UsersEditForm;

class UsersController extends \BaseController {

	/**
	* @var usersEditForm
	*/
	protected $usersEditForm;

	/**
	* @param UsersEditForm $usersEditForm
	*/
	function __construct(UsersEditForm $usersEditForm)
	{
		$this->usersEditForm = $usersEditForm;

		$this->beforeFilter('currentUser', ['only' => ['show', 'edit', 'update']]);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::findOrFail($id);

		return View::make('protected.user.show')->withUser($user);
		
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::findOrFail($id);

		return View::make('protected.user.edit')->withUser($user);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$user = User::findOrFail($id);

		if (! Input::has("password"))
		{
			$input = Input::only('email', 'first_name', 'last_name');

			$this->usersEditForm->validateUpdate($user->id, $input);

			$user->fill($input)->save();

			return Redirect::route('profiles.edit', $user->id)->withFlashMessage('User has been updated successfully!');
		}

		else
		{
			$input = Input::only('email', 'first_name', 'last_name', 'password', 'password_confirmation');

			$this->usersEditForm->validateUpdate($user->id, $input);

			$input = Input::only('email', 'first_name', 'last_name', 'password');

			$user->fill($input)->save();

			$user->save();

			return Redirect::route('profiles.edit', $user->id)->withFlashMessage('User (and password) has been updated successfully!');
		}
	}



}