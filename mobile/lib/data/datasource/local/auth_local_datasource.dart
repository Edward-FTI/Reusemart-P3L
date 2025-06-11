import 'dart:developer';

import 'package:shared_preferences/shared_preferences.dart';

import '../../models/respons/auth_respon_model.dart';

class AuthLocalDatasource {
  Future<void> saveUserData(AuthResponModel data) async {
    // save user data to local storage
    final pref = await SharedPreferences.getInstance();
    log("Data User: ${data.toJson()}");
    await pref.setString('user', data.toJson());
  }

  //remove user data from local storage
  Future<void> removeUserData() async {
    final pref = await SharedPreferences.getInstance();
    await pref.remove('user');
  }

  //get user data from local storage
  Future<AuthResponModel?> getUserData() async {
    final pref = await SharedPreferences.getInstance();
    final user = pref.getString('user');
    if (user != null) {
      return AuthResponModel.fromJson(user);
    } else {
      return null;
    }
  }

  //check if user is logged in
  Future<bool> isLoggedIn() async {
    final pref = await SharedPreferences.getInstance();
    final user = pref.getString('user');
    return user != null;
  }
}
