import 'dart:developer';
import 'package:flutter/material.dart';
import 'package:mobile/Hunter/history_hunter.dart';
import 'package:mobile/data/datasource/Hunter/hunter_data_source.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/datasource/Hunter/historyHunter_data_source.dart';
import 'package:mobile/data/models/hunter/ModelHunter.dart';
import 'package:mobile/screen/Home_screen.dart';

class ProfileHunterPage extends StatefulWidget {
  const ProfileHunterPage({super.key});

  @override
  State<ProfileHunterPage> createState() => _ProfileHunterPageState();
}

class _ProfileHunterPageState extends State<ProfileHunterPage> {
  HunterModel? hunter;

  @override
  void initState() {
    super.initState();
    loadHunterData();
  }

  Future<void> loadHunterData() async {
    final data = await HunterDataSource().getHunter();
    if (data != null) {
      log("Hunter berhasil dimuat: ${data.toString()}");
      setState(() {
        hunter = data;
      });
    } else {
      log("Hunter == null, tidak bisa dimuat.");
    }
  }

  @override
  Widget build(BuildContext context) {
    if (hunter == null) {
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
              SizedBox(height: 24),
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
        title: const Text("Profil Hunter"),
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
              hunter!.name ?? 'Nama Hunter',
              style: const TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              "ID: ${hunter!.id ?? '-'}",
              style: const TextStyle(
                fontSize: 16,
                color: Colors.grey,
              ),
            ),
            const Divider(),

            // Info tambahan
            buildInfoRow("Email", hunter!.email ?? '-'),
            buildInfoRow("Tanggal Lahir", hunter!.tglLahir ?? '-'),
            buildInfoRow("Jabatan", hunter!.jabatan ?? '-'),
            buildInfoRow("Komisi", "Rp${hunter!.gaji ?? 0}"),

            const SizedBox(height: 30),
            ElevatedButton(
              onPressed: () => Navigator.of(context).push(
                MaterialPageRoute(
                  builder: (context) => const HistoryHunter()
                  // builder: (context) => const HomeScreen()
                ),
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.green,
                padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              child: const Text(
                "Lihat History Hunter",
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
            width: 120,
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
