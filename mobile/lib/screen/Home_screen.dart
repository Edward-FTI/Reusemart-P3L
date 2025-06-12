// lib/screens/home_screen.dart
import 'package:flutter/material.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:mobile/data/models/barang/barang.dart';
import 'package:mobile/data/service/api_service.dart';
import 'package:mobile/screen/barang_detail_modal.dart';
import 'package:mobile/login/login.dart';
import 'package:mobile/Pembeli/pembeli.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late Future<List<Barang>> _allBarangFuture;
  List<Barang> _sliderBarang = [];

  @override
  void initState() {
    super.initState();

    _allBarangFuture = ApiService().fetchAllBarang();

    _allBarangFuture.then((barangList) {
      if (!mounted) return;

      setState(() {
        // Filter barang untuk slider berdasar id 1 sampai 3
        _sliderBarang = barangList
            .where((barang) => barang.id >= 1 && barang.id <= 3)
            .toList();

        // Jika kosong, ambil 3 barang pertama sebagai fallback
        if (_sliderBarang.isEmpty && barangList.isNotEmpty) {
          _sliderBarang = barangList.take(3).toList();
        }
      });
    }).catchError((error) {
      // Pastikan context sudah mounted saat memanggil snackbar
      if (!mounted) return;
      WidgetsBinding.instance.addPostFrameCallback((_) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal memuat barang untuk slider: $error')),
        );
      });
    });
  }

  void _showBarangDetailModal(Barang barang) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return BarangDetailModalSimple(barang: barang);
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: _buildAppBar(),
      body: FutureBuilder<List<Barang>>(
        future: _allBarangFuture,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(child: Text('Tidak ada barang ditemukan.'));
          } else {
            final allBarang = snapshot.data!;

            return SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildBarangSlider(),
                  const Padding(
                    padding: EdgeInsets.all(16.0),
                    child: Text(
                      'Rekomendasi Pilihan',
                      style:
                          TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                  ),
                  GridView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    padding: const EdgeInsets.symmetric(horizontal: 16.0),
                    gridDelegate:
                        const SliverGridDelegateWithFixedCrossAxisCount(
                      crossAxisCount: 2,
                      crossAxisSpacing: 10.0,
                      mainAxisSpacing: 10.0,
                      childAspectRatio: 0.7,
                    ),
                    itemCount: allBarang.length,
                    itemBuilder: (context, index) {
                      final barang = allBarang[index];
                      return _buildBarangCard(barang);
                    },
                  ),
                ],
              ),
            );
          }
        },
      ),
      bottomNavigationBar: _buildBottomNavBar(),
    );
  }

  AppBar _buildAppBar() {
    return AppBar(
      backgroundColor: Colors.white,
      elevation: 0.5,
      toolbarHeight: 60,
      titleSpacing: 0,
      title: Padding(
        padding: const EdgeInsets.only(left: 16.0),
        child: Row(
          children: [
            const Text(
              'reusemart',
              style: TextStyle(
                color: Colors.green,
                fontSize: 18,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Container(
                height: 38,
                decoration: BoxDecoration(
                  color: Colors.grey[200],
                  borderRadius: BorderRadius.circular(8),
                ),
                child: TextField(
                  decoration: InputDecoration(
                    hintText: 'Cari di reusemart',
                    hintStyle: TextStyle(color: Colors.grey[600], fontSize: 14),
                    prefixIcon:
                        const Icon(Icons.search, color: Colors.grey, size: 20),
                    border: InputBorder.none,
                    contentPadding:
                        const EdgeInsets.symmetric(vertical: 8, horizontal: 10),
                  ),
                  style: const TextStyle(fontSize: 14),
                ),
              ),
            ),
          ],
        ),
      ),
      actions: [
        IconButton(
          icon: const Icon(Icons.notifications_none, color: Colors.grey),
          onPressed: () {},
        ),
        IconButton(
          icon: const Icon(Icons.login, color: Colors.grey),
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => const LoginPage()),
            );
          },
        ),
      ],
    );
  }

  Widget _buildBarangSlider() {
    if (_sliderBarang.isEmpty) {
      return const SizedBox.shrink();
    }
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 16.0),
      child: CarouselSlider.builder(
        itemCount: _sliderBarang.length,
        itemBuilder: (context, index, realIndex) {
          final barang = _sliderBarang[index];
          return GestureDetector(
            onTap: () => _showBarangDetailModal(barang),
            child: Container(
              margin: const EdgeInsets.symmetric(horizontal: 5.0),
              decoration: BoxDecoration(
                color: Colors.grey[100],
                borderRadius: BorderRadius.circular(10),
                image: DecorationImage(
                  image: NetworkImage(barang.fullGambarUrl),
                  fit: BoxFit.cover,
                  onError: (error, stackTrace) {},
                ),
              ),
              child: Align(
                alignment: Alignment.bottomLeft,
                child: Container(
                  margin: const EdgeInsets.all(8),
                  padding:
                      const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: Colors.black.withOpacity(0.6),
                    borderRadius: BorderRadius.circular(5),
                  ),
                  child: Text(
                    barang.namaBarang,
                    style: const TextStyle(
                      fontSize: 14,
                      color: Colors.white,
                      fontWeight: FontWeight.bold,
                    ),
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
              ),
            ),
          );
        },
        options: CarouselOptions(
          height: 180.0,
          enlargeCenterPage: true,
          autoPlay: true,
          aspectRatio: 16 / 9,
          autoPlayCurve: Curves.fastOutSlowIn,
          enableInfiniteScroll: true,
          autoPlayAnimationDuration: const Duration(milliseconds: 800),
          viewportFraction: 0.85,
        ),
      ),
    );
  }

  Widget _buildBarangCard(Barang barang) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
      clipBehavior: Clip.antiAlias,
      child: InkWell(
        onTap: () => _showBarangDetailModal(barang),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Expanded(
              flex: 3,
              child: FadeInImage.assetNetwork(
                placeholder: 'assets/placeholder.png',
                image: barang.fullGambarUrl,
                fit: BoxFit.cover,
                width: double.infinity,
                imageErrorBuilder: (context, error, stackTrace) {
                  return Image.asset(
                    'assets/image_error.png',
                    fit: BoxFit.cover,
                    width: double.infinity,
                  );
                },
              ),
            ),
            Expanded(
              flex: 2,
              child: Padding(
                padding: const EdgeInsets.all(8.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      barang.namaBarang,
                      style: const TextStyle(
                          fontSize: 14, fontWeight: FontWeight.w600),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      barang.formattedHarga,
                      style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: Colors.green),
                    ),
                    const Spacer(),
                    Row(
                      children: [
                        const Icon(Icons.star, color: Colors.amber, size: 16),
                        Text('4.5',
                            style: TextStyle(
                                fontSize: 12, color: Colors.grey[600])),
                        const SizedBox(width: 4),
                        Expanded(
                          child: Text(
                            ' (100+ terjual)',
                            style: TextStyle(
                                fontSize: 12, color: Colors.grey[600]),
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  int _currentIndex = 0; // Tambahkan ini di atas dalam _HomeScreenState

  BottomNavigationBar _buildBottomNavBar() {
    return BottomNavigationBar(
      type: BottomNavigationBarType.fixed,
      selectedItemColor: Colors.green,
      unselectedItemColor: Colors.grey,
      showUnselectedLabels: true,
      currentIndex: _currentIndex,
      onTap: (index) {
        setState(() {
          _currentIndex = index;
        });

        if (index == 3) {
          // Navigasi ke halaman profil/akun
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => ProfilePembeliPage()),
          );
        }

        // Tambahkan navigasi lain jika diperlukan untuk index 1 dan 2
      },
      items: const [
        BottomNavigationBarItem(
            icon: Icon(Icons.home_outlined), label: 'Beranda'),
        BottomNavigationBarItem(
            icon: Icon(Icons.category_outlined), label: 'Kategori'),
        BottomNavigationBarItem(
            icon: Icon(Icons.inbox_outlined), label: 'Inbox'),
        BottomNavigationBarItem(
            icon: Icon(Icons.person_outline), label: 'Akun'),
      ],
    );
  }
}
