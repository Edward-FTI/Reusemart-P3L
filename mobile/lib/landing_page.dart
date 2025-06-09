import 'package:flutter/material.dart';
import 'package:mobile/login/login.dart';

class LandingPage extends StatelessWidget {
  const LandingPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              // Header
              Container(
                padding: const EdgeInsets.all(24),
                color: Colors.green[700],
                width: double.infinity,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    //
                    Image.asset(
                      'assets/images/logo.png',
                      width: 165,
                    ),
                    Text(
                      'ReuseMart',
                      style: TextStyle(
                        fontSize: 28,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                    SizedBox(height: 8),
                    Text(
                      'Barang Bekas Berkualitas\nHarga Terjangkau',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        color: Colors.white70,
                        fontSize: 14,
                      ),
                    ),
                  ],
                ),
              ),

              SizedBox(
                height: 8,
              ),

              // Visi dan Misi
              Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: const [
                    Text(
                      'Visi ReuseMart',
                      style: TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    SizedBox(height: 8),
                    Text(
                      'Mengurangi penumpukan sampah dengan memberi kesempatan kedua bagi barang bekas yang masih layak pakai.',
                      textAlign: TextAlign.center,
                    ),
                  ],
                ),
              ),

              SizedBox(
                height: 16,
              ),

              Text(
                'Kategori Barang',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              SizedBox(
                height: 8,
              ),
              // Kategori
              SingleChildScrollView(
                scrollDirection: Axis.horizontal,
                child: Row(
                  children: const [
                    Padding(
                      padding: EdgeInsets.symmetric(horizontal: 16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          // Tambahan untuk memberi jarak dari atas
                          SizedBox(height: 12),
                          Wrap(
                            spacing: 16,
                            runSpacing: 16,
                            children: [
                              CategoryIcon(
                                title: 'Elektronik & Gadget',
                                icon: Icons.devices, // ✅ Sudah cocok
                                width: 80,
                              ),
                              CategoryIcon(
                                title: 'Pakaian & Aksesori',
                                icon: Icons
                                    .shopping_bag, // 🎯 Lebih umum untuk fashion
                                width: 80,
                              ),
                              CategoryIcon(
                                title: 'Perabotan Rumah',
                                icon: Icons.weekend, // 🪑 Sofa/living room
                                width: 80,
                              ),
                              CategoryIcon(
                                title: 'Buku & Alat Tulis',
                                icon: Icons.menu_book, // ✅ Sudah cocok
                                width: 80,
                              ),
                              CategoryIcon(
                                title: 'Mainan & Koleksi',
                                icon: Icons
                                    .extension, // 🧩 Puzzle sebagai ikon mainan
                                width: 80,
                              ),
                              CategoryIcon(
                                title: 'Perlengkapan Bayi & Anak',
                                icon: Icons
                                    .child_friendly, // 👶 Cocok untuk anak/bayi
                                width: 80,
                              ),
                              CategoryIcon(
                                title: 'Otomotif',
                                icon: Icons.directions_car, // ✅ Sudah cocok
                                width: 80,
                              ),
                              CategoryIcon(
                                title: 'Taman & Outdoor',
                                icon: Icons.park, // 🌳 Lebih representatif
                                width: 80,
                              ),
                              CategoryIcon(
                                title: 'Peralatan Kantor',
                                icon: Icons
                                    .desktop_windows, // 🖥️ Komputer untuk kantor
                                width: 80,
                              ),
                              CategoryIcon(
                                title: 'Kosmetik & Perawatan',
                                icon: Icons
                                    .face_retouching_natural, // 💄 Lebih cocok untuk kosmetik
                                width: 80,
                              ),
                            ],
                          ),
                          // Tambahan jarak bawah agar tidak kepotong
                        ],
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 40),
              // CTA Button
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 24),
                child: Column(
                  children: [
                    ElevatedButton(
                      onPressed: () {
                        // Navigasi ke halaman login
                        Navigator.of(context)
                            .push(MaterialPageRoute(builder: (context) {
                          return LoginPage();
                        }));
                      },
                      style: ElevatedButton.styleFrom(
                        minimumSize: const Size.fromHeight(50),
                        backgroundColor: Colors.green,
                      ),
                      child: const Text(
                        'Lanjutkan',
                        style: TextStyle(
                          color: Colors.white,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

// Komponen ikon kategori
class CategoryIcon extends StatelessWidget {
  final String title;
  final IconData icon;
  final double width;

  const CategoryIcon({
    Key? key,
    required this.title,
    required this.icon,
    this.width = 120,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: width,
      child: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.grey.shade200,
              shape: BoxShape.circle,
            ),
            child: Icon(icon, size: 32, color: Colors.teal),
          ),
          const SizedBox(height: 8),
          Text(
            title,
            textAlign: TextAlign.center,
            style: const TextStyle(fontSize: 12),
          ),
        ],
      ),
    );
  }
}
