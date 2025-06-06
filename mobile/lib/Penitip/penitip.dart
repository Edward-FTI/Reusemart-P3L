import 'dart:developer';
import 'package:flutter/material.dart';
import 'package:mobile/Penitip/HistoryPenitip.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/models/penitip/PenitipModel.dart';
import 'package:mobile/data/datasource/penitip/penitip_data_source.dart';

class ProfilePenitipPage extends StatefulWidget {
  const ProfilePenitipPage({super.key});

  @override
  State<ProfilePenitipPage> createState() => _ProfilePenitipPageState();
}

class _ProfilePenitipPageState extends State<ProfilePenitipPage> {
  Map<String, dynamic>? userData;
  PenitipModel? penitip;

  @override
  void initState() {
    super.initState();
    // loadUserData();
    loadPenitipData();
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

  Future<void> loadPenitipData() async {
    final data = await PenitipDataSource().getPenitip();
    if (data != null) {
      log("Penitip berhasil dimuat: ${data.toJson()}");
      setState(() {
        penitip = data;
      });
    } else {
      log("Penitip == null, tidak bisa dimuat.");
    }
  }

  @override
  Widget build(BuildContext context) {
    if (penitip == null) {
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
        title: const Text("Profil Penitip"),
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
              penitip!.nama_penitip ?? 'Nama Penitip',
              style: const TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              penitip!.email ?? 'Email',
              style: const TextStyle(
                fontSize: 16,
                color: Colors.grey,
              ),
            ),
            // const SizedBox(height: 24),
            Divider(),

            // Informasi data penitip
            buildInfoRow("Nomor KTP", penitip!.no_ktp ?? "no_ktp"),
            buildInfoRow("Alamat", penitip!.alamat ?? "alamat"),
            buildInfoRow("Saldo", penitip!.saldo.toString()),
            buildInfoRow("Point", penitip!.point.toString()),
            buildInfoRow("Badge", penitip!.badge ?? "-"),
            const SizedBox(height: 30),
            ElevatedButton(
              onPressed: () {
                // TODO: Navigasi ke halaman history
                Navigator.of(context)
                    .push(MaterialPageRoute(builder: (context) {
                  return HistoryPenitipanPage();
                }));
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.green,
                padding:
                    const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              child: const Text(
                "Lihat History Penitipan",
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
            width: 90,
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
