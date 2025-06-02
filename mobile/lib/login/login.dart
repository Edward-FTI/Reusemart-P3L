import 'package:flutter/material.dart';
import 'package:mobile/Hunter/hunter.dart';
import 'package:mobile/Kurir/kurir.dart';
import 'package:mobile/Pembeli/pembeli.dart';
import 'package:mobile/Penitip/penitip.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/datasource/remote/auth_remote_datasource.dart';
// import 'package:mobile_design/profile/profile_pembeli.dart';

class LoginPage extends StatefulWidget {
  LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();

  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();

  @override
  void dispose() {
    emailController.dispose();
    passwordController.dispose();
    super.dispose();
  }

  void _login(String email, String password) async {
    // form validation
    if (_formKey.currentState!.validate()) {
      final result = await AuthRemoteDatasource().login(email, password);
      if (result != null) {
        await AuthLocalDatasource().saveUserData(result);
        String role = result.user!.role!;
        // If login is successful, navigate to the profile page
        if (role == 'Pembeli') {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => ProfilePembeliPage()),
          );
        } else if (role == 'Penitip') {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => ProfilePenitipPage()),
          );
        } else if (role == 'Hunter') {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => ProfileHunterPage()),
          );
        } else if (role == 'Kurir') {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(builder: (context) => ProfileKurirPage()),
          );
        } else {
          const snackBar = SnackBar(
            content: Text('Role not recognized, please contact support.'),
          );
          ScaffoldMessenger.of(context).showSnackBar(snackBar);
        }
        const snackBar = SnackBar(
          content: Text('Login successful!'),
        );
        ScaffoldMessenger.of(context).showSnackBar(snackBar);
      } else {
        // If login fails, display a snackbar with an error message
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Login failed')),
        );
      }
    } else {
      // If the form is not valid, display a snackbar with an error message
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please fill in all fields')),
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
                          decoration: const InputDecoration(
                            hintText: 'Email',
                            filled: true,
                            fillColor: Color(0xFFF5FCF9),
                            contentPadding: EdgeInsets.symmetric(
                                horizontal: 16.0 * 1.5, vertical: 16.0),
                            border: OutlineInputBorder(
                              borderSide: BorderSide.none,
                              borderRadius:
                                  BorderRadius.all(Radius.circular(50)),
                            ),
                          ),
                          controller: emailController,
                          validator: (value) {
                            if (value == null || value.isEmpty) {
                              return 'Please enter your email';
                            }
                            // Add more validation if needed
                            return null;
                          },
                          onSaved: (email) {
                            // Save it
                          },
                        ),
                        Padding(
                          padding: const EdgeInsets.symmetric(vertical: 16.0),
                          child: TextFormField(
                            obscureText: true,
                            decoration: const InputDecoration(
                              hintText: 'Password',
                              filled: true,
                              fillColor: Color(0xFFF5FCF9),
                              contentPadding: EdgeInsets.symmetric(
                                  horizontal: 16.0 * 1.5, vertical: 16.0),
                              border: OutlineInputBorder(
                                borderSide: BorderSide.none,
                                borderRadius:
                                    BorderRadius.all(Radius.circular(50)),
                              ),
                            ),
                            controller: passwordController,
                            validator: (value) {
                              if (value == null || value.isEmpty) {
                                return 'Please enter your password';
                              }
                              // Add more validation if needed
                              return null;
                            },
                            onSaved: (passaword) {
                              // Save it
                            },
                          ),
                        ),
                        SizedBox(height: 20),

                        // ini button untuk arahkan ke halaman setelah login
                        ElevatedButton(
                          onPressed: () {
                            _login(emailController.text, passwordController.text);
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: const Color(0xFF00BF6D),
                            foregroundColor: Colors.white,
                            minimumSize: const Size(double.infinity, 48),
                            // shape: const StadiumBorder(),
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



