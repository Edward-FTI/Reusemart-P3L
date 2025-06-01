
import 'dart:convert';

class AuthResponModel {
    String? message;
    String? accessToken;
    String? tokenType;
    User? user;

    AuthResponModel({
        this.message,
        this.accessToken,
        this.tokenType,
        this.user,
    });
factory AuthResponModel.fromJson(String str) =>
      AuthResponModel.fromMap(json.decode(str));

  String toJson() => json.encode(toMap());
    factory AuthResponModel.fromMap(Map<String, dynamic> json) => AuthResponModel(
        message: json["message"],
        accessToken: json["access_token"],
        tokenType: json["token_type"],
        user: json["user"] == null ? null : User.fromJson(json["user"]),
    );

    Map<String, dynamic> toMap() => {
        "message": message,
        "access_token": accessToken,
        "token_type": tokenType,
        "user": user?.toJson(),
    };
}

class User {
    int? id;
    String? name;
    String? email;
    DateTime? emailVerifiedAt;
    String? role;
    DateTime? createdAt;
    DateTime? updatedAt;

    User({
        this.id,
        this.name,
        this.email,
        this.emailVerifiedAt,
        this.role,
        this.createdAt,
        this.updatedAt,
    });

    factory User.fromJson(Map<String, dynamic> json) => User(
        id: json["id"],
        name: json["name"],
        email: json["email"],
        emailVerifiedAt: json["email_verified_at"] == null ? null : DateTime.parse(json["email_verified_at"]),
        role: json["role"],
        createdAt: json["created_at"] == null ? null : DateTime.parse(json["created_at"]),
        updatedAt: json["updated_at"] == null ? null : DateTime.parse(json["updated_at"]),
    );

    Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "email": email,
        "email_verified_at": emailVerifiedAt?.toIso8601String(),
        "role": role,
        "created_at": createdAt?.toIso8601String(),
        "updated_at": updatedAt?.toIso8601String(),
    };
}
