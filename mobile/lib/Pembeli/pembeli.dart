import 'dart:developer';
import 'package:flutter/material.dart';
import 'package:mobile/Pembeli/history_pembeli.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/models/pembeli/ModelPembeli.dart';
import 'package:mobile/data/datasource/pembeli/pembeli_data_source.dart';
import 'package:mobile/screen/merchandise_screen.dart';

class ProfilePembeliPage extends StatefulWidget {
  const ProfilePembeliPage({super.key});

  @override
  State<ProfilePembeliPage> createState() => _ProfilePembeliPageState();
}

class _ProfilePembeliPageState extends State<ProfilePembeliPage> {
  Map<String, dynamic>? userData;
  PembeliModel? pembeli;

  @override
  void initState() {
    super.initState();
    // loadUserData();
    loadPembeliData();
  }

  // Future<void> loadUserData() async {
  //   final authData = await AuthLocalDatasource().getUserData();
  //   if (authData != null && authData.user != null) {
  //     userData = {
  //       "nama_pembeli": authData.user!.name,
  //       "email": authData.user!.email,
  //       "role": authData.user!.role,
  //     };
  //   }
  //   setState(() {});
  //   log("user data: ${userData.toString()}");
  // }

  Future<void> loadPembeliData() async {
    final data = await PembeliDataSource().getPembeli();
    if (data != null) {
      log("Pembeli berhasil dimuat: ${data.toJson()}");
      setState(() {
        pembeli = data;
      });
    } else {
      log("Pembeli == null, tidak bisa dimuat.");
    }
  }

  @override
  Widget build(BuildContext context) {
    if (pembeli == null) {
      return const Scaffold(
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              SizedBox(
                width: 50,
                height: 50,
                child: CircularProgressIndicator(),
              ),
              Divider(
                color: Colors.transparent,
                height: 24,
              ),
              Text(
                "Loading...",
                style: TextStyle(fontSize: 16, color: Colors.black54),
              ),
            ],
          ),
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        title: const Text("Profil Pembeli"),
        backgroundColor: Colors.blue,
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            const SizedBox(height: 24),
            Center(
              child: CircleAvatar(
                radius: 60,
                backgroundImage: NetworkImage(
                  'https://img.icons8.com/?size=100&id=NPW07SMh7Aco&format=png&color=000000',
                ),
                backgroundColor: Colors.grey[200],
              ),
            ),
            const SizedBox(height: 16),
            Text(
              pembeli!.nama_pembeli ?? 'Nama Pembeli',
              style: const TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              pembeli!.email ?? 'Email',
              style: const TextStyle(
                fontSize: 16,
                color: Colors.grey,
              ),
            ),
            // const SizedBox(height: 24),
            Divider(),

            // Informasi data pembeli
            buildInfoRow("Nomor HP", pembeli!.no_hp ?? "-"),
            buildInfoRow("Saldo", "Rp${pembeli!.saldo}"),
            buildInfoRow("Point", pembeli!.point.toString()),

            const SizedBox(height: 30),
            ElevatedButton(
              onPressed: () => Navigator.of(context).push(
                MaterialPageRoute(
                  builder: (context) {
                    return MerchandiseScreen();
                  },
                ),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.green,
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              child: const Text(
                "Tukar Point",
                style: TextStyle(fontSize: 18, color: Colors.white),
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () => Navigator.of(context).push(
                MaterialPageRoute(
                  builder: (context) {
                    return HistoryPembeli();
                  },
                ),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.green,
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              child: const Text(
                "Lihat History Pembelian",
                style: TextStyle(fontSize: 18, color: Colors.white),
              ),
            ),
            const SizedBox(height: 20),
          ],
        ),
      ),
    );
  }

  Widget buildInfoRow(String label, String value) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      decoration: BoxDecoration(
        color: Colors.grey[100],
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.grey.shade300),
      ),
      child: Row(
        children: [
          SizedBox(
            width: 100,
            child: Text(
              label,
              style: const TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 16,
              ),
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontSize: 16),
            ),
          ),
        ],
      ),
    );
  }
}
