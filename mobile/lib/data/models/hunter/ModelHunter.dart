class HunterModel {
  final int? id;
  final String? name;
  final String? email;
  final String? tglLahir;
  final String? jabatan;
  final int? gaji;

  HunterModel({
    this.id,
    this.name,
    this.email,
    this.tglLahir,
    this.jabatan,
    this.gaji,
  });

  factory HunterModel.fromJson(Map<String, dynamic> json) {
    return HunterModel(
      id: json['id'],
      name: json['nama'],
      email: json['email'],
      tglLahir: json['tgl_lahir'],
      jabatan: json['jabatan'],
      gaji: json['gaji'],
    );
  }
}
