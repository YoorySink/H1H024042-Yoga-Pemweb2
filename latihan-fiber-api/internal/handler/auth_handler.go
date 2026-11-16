package handler
import (
	"errors"
	"time"
"github.com/gofiber/fiber/v3"
"golang.org/x/crypto/bcrypt"
"latihan-fiber-api/internal/auth"
"latihan-fiber-api/internal/config"
"latihan-fiber-api/internal/model"
"latihan-fiber-api/internal/validasi"
)
func Registrasi(c fiber.Ctx) error {
var permintaan PermintaanRegistrasi
err := c.Bind().Body(&permintaan)
if err != nil {
return fiber.NewError(fiber.StatusBadRequest, "Format body tidak valid")
}
galat := validasi.Periksa(permintaan)
if galat != nil {
return c.Status(fiber.StatusUnprocessableEntity).JSON(fiber.Map{
"sukses": false,
"pesan": "Data yang dikirim tidak valid",
"galat": galat,
})
}
hash, err := bcrypt.GenerateFromPassword([]byte(permintaan.KataSandi),
bcrypt.DefaultCost)
if err != nil {
return fiber.NewError(fiber.StatusInternalServerError, "Gagal memproses kata sandi")
}
pengguna := model.Pengguna{
Nama: permintaan.Nama,
Email: permintaan.Email,
KataSandi: string(hash),
Peran: "mahasiswa",
}
err = config.DB.Create(&pengguna).Error
if err != nil {
return fiber.NewError(fiber.StatusUnprocessableEntity, "Email sudah terdaftar")
}
return c.Status(fiber.StatusCreated).JSON(fiber.Map{
"sukses": true,
"pesan": "Registrasi berhasil",
"data": pengguna,
})
}
func Login(c fiber.Ctx) error {
var permintaan PermintaanLogin
err := c.Bind().Body(&permintaan)
if err != nil {
return fiber.NewError(fiber.StatusBadRequest, "Format body tidak valid")
}
galat := validasi.Periksa(permintaan)
if galat != nil {
return c.Status(fiber.StatusUnprocessableEntity).JSON(fiber.Map{
"sukses": false,
"pesan": "Data yang dikirim tidak valid",
"galat": galat,
})
}
var pengguna model.Pengguna
err = config.DB.Where("email = ?",
permintaan.Email).First(&pengguna).Error
if err != nil {
return fiber.NewError(fiber.StatusUnauthorized, "Email atau kata sandi tidak sesuai")
}
err = bcrypt.CompareHashAndPassword([]byte(pengguna.KataSandi),
[]byte(permintaan.KataSandi))
if err != nil {
return fiber.NewError(fiber.StatusUnauthorized, "Email atau kata sandi tidak sesuai")
}
konf := config.Muat()
token, err := auth.TerbitkanToken(pengguna.ID, pengguna.Peran,
konf.JwtSecret, konf.JwtTtlMenit)
if err != nil {
return fiber.NewError(fiber.StatusInternalServerError, "Gagal menerbitkan token")
}
return c.JSON(fiber.Map{
"sukses": true,
"pesan": "Login berhasil",
"data": fiber.Map{
"pengguna": pengguna,
"token": token,
"tipe": "Bearer",
},
})
}
func Profil(c fiber.Ctx) error {
idPengguna := c.Locals("id_pengguna")
var pengguna model.Pengguna
err := config.DB.First(&pengguna, idPengguna).Error
if err != nil {
return fiber.NewError(fiber.StatusNotFound, "Pengguna tidak ditemukan")
}
return c.JSON(fiber.Map{
"sukses": true,
"data": pengguna,
})

}
func Segarkan(c fiber.Ctx) error {
	idPengguna, ok := c.Locals("id_pengguna").(uint)
	if !ok {
		return fiber.NewError(fiber.StatusUnauthorized, "Identitas pengguna tidak ditemukan")
	}

	var pengguna model.Pengguna
	err := config.DB.First(&pengguna, idPengguna).Error
	if err != nil {
		return fiber.NewError(fiber.StatusUnauthorized, "Pengguna tidak ditemukan")
	}

	konf := config.Muat()
	tokenBaru, err := auth.TerbitkanToken(pengguna.ID, pengguna.Peran,
		konf.JwtSecret, konf.JwtTtlMenit)
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal menerbitkan token")
	}
	err = cabutToken(c)
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mencabut token lama")
	}
	return c.JSON(fiber.Map{
		"sukses": true,
		"pesan":  "Token berhasil disegarkan",
		"data": fiber.Map{
			"token": tokenBaru,
			"tipe":  "Bearer",
		},
	})
}

func cabutToken(c fiber.Ctx) error {
	jti, okJti := c.Locals("jti").(string)
	kedaluwarsa, okExp := c.Locals("token_kedaluwarsa").(time.Time)
	if !okJti || !okExp {
		return errors.New("data token tidak tersedia pada konteks")
	}
	entri := model.TokenDaftarHitam{Jti: jti, KedaluwarsaPada: kedaluwarsa}
	return config.DB.Create(&entri).Error
}

func Logout(c fiber.Ctx) error {
	err := cabutToken(c)
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mencabut token")
	}
	config.DB.Where("kedaluwarsa_pada < ?", time.Now()).Delete(&model.TokenDaftarHitam{})
	return c.JSON(fiber.Map{
		"sukses": true,
		"pesan":  "Logout berhasil, token dicabut",
	})
}