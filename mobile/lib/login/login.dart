import 'package:flutter/material.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:mobile/Hunter/hunter.dart';
import 'package:mobile/Kurir/kurir.dart';
import 'package:mobile/Pembeli/pembeli.dart';
// import 'package:mobile/data/service/reward_service.dart';
import 'package:mobile/screen/merchandise_screen.dart';
import 'package:mobile/Penitip/penitip.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/datasource/remote/auth_remote_datasource.dart';
import 'package:mobile/data/datasource/remote/user_remote_datasource.dart';

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final emailController = TextEditingController();
  final passwordController = TextEditingController();

  @override
  void dispose() {
    emailController.dispose();
    passwordController.dispose();
    super.dispose();
  }

  Future<void> _login(String email, String password) async {
    if (_formKey.currentState!.validate()) {
      final result = await AuthRemoteDatasource().login(email, password);

      if (result != null) {
        await AuthLocalDatasource().saveUserData(result);

        
        final fcmToken = await FirebaseMessaging.instance.getToken();
        if (fcmToken != null) {
          await UserRemoteDatasource().updateFcmToken(fcmToken);
        }

        
        String role = result.user!.role!;
        Widget? nextPage;
        switch (role) {
          case 'Pembeli':
            // nextPage = ProfilePembeliPage();
            nextPage = MerchandiseScreen();
            break;
          case 'Penitip':
            nextPage = ProfilePenitipPage();
            break;
          case 'Hunter':
            nextPage = ProfileHunterPage();
            break;
          case 'Kurir':
            nextPage = ProfileKurirPage();
            break;
          default:
            ScaffoldMessenger.of(context).showSnackBar(
              const SnackBar(content: Text('Role tidak dikenali.')),
            );
            return;
        }

        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (_) => nextPage!),
        );

        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Login berhasil!')),
        );
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Login gagal, cek email dan password.')),
        );
      }
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Mohon isi semua field.')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: LayoutBuilder(
          builder: (context, constraints) {
            return SingleChildScrollView(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Column(
                children: [
                  SizedBox(height: constraints.maxHeight * 0.1),
                  Text(
                    "Login",
                    style: TextStyle(fontSize: 50, fontWeight: FontWeight.w800),
                  ),
                  SizedBox(height: constraints.maxHeight * 0.05),
                  Form(
                    key: _formKey,
                    child: Column(
                      children: [
                        TextFormField(
                          controller: emailController,
                          decoration: const InputDecoration(
                            hintText: 'Email',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                                horizontal: 24.0, vertical: 16.0),
                            border: OutlineInputBorder(
                              borderSide: BorderSide.none,
                              borderRadius: BorderRadius.all(Radius.circular(50)),
                            ),
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Masukkan email';
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: 16),
                        TextFormField(
                          controller: passwordController,
                          obscureText: true,
                          decoration: const InputDecoration(
                            hintText: 'Password',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                                horizontal: 24.0, vertical: 16.0),
                            border: OutlineInputBorder(
                              borderSide: BorderSide.none,
                              borderRadius: BorderRadius.all(Radius.circular(50)),
                            ),
                          ),
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Masukkan password';
                            }
                            return null;
                          },
                        ),
                        const SizedBox(height: 20),
                        ElevatedButton(
                          onPressed: () => _login(
                            emailController.text,
                            passwordController.text,
                          ),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: const Color(0xFF00BF6D),
                            foregroundColor: Colors.white,
                            minimumSize: const Size(double.infinity, 48),
                          ),
                          child: const Text("Sign in"),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            );
          },
        ),
      ),
    );
  }
}
