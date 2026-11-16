package router

import (
	"github.com/gofiber/fiber/v3"
	"latihan-fiber-api/internal/config"
	"latihan-fiber-api/internal/handler"
	"latihan-fiber-api/internal/middleware"
)

func Daftarkan(app *fiber.App, konf config.Konfigurasi) {
	api := app.Group("/api")

	api.Get("/status", func(c fiber.Ctx) error {
		return c.JSON(fiber.Map{
			"sukses": true,
			"pesan":  "API Fiber aktif",
		})
	})
	api.Get("/program-studi", handler.DaftarProgramStudi)

	// // mahasiswa := api.Group("/mahasiswa", middleware.KunciApi(konf.KunciApi))
	// mahasiswa.Get("/", handler.DaftarMahasiswa)
	// // mahasiswa.Get("/nim/:nim", handler.DetailMahasiswaByNim)
	// mahasiswa.Get("/:id", handler.DetailMahasiswa)
	api.Post("/auth/registrasi", handler.Registrasi)
	api.Post("/auth/login", handler.Login)

	terlindungi := api.Group("", middleware.Autentikasi())
	terlindungi.Get("/auth/profil", handler.Profil)
	terlindungi.Post("/auth/segarkan", handler.Segarkan)
	
	mahasiswa := terlindungi.Group("/mahasiswa")
	terlindungi.Post("/auth/logout", handler.Logout)


	// mahasiswa := api.Group("/mahasiswa")
	mahasiswa.Get("/", handler.DaftarMahasiswa)
	mahasiswa.Post("/", middleware.HanyaPeran("admin", "dosen"), handler.BuatMahasiswa)
	mahasiswa.Get("/:id", handler.DetailMahasiswa)
	mahasiswa.Put("/:id", middleware.HanyaPeran("admin", "dosen"), handler.PerbaruiMahasiswa)
	mahasiswa.Delete("/:id", middleware.HanyaPeran("admin"), handler.HapusMahasiswa)
	mahasiswa.Post("/:id/pulihkan", handler.PulihkanMahasiswa)

	matakuliah := terlindungi.Group("/matakuliah")
	// matakuliah := api.Group("/matakuliah")
	matakuliah.Get("/", handler.DaftarMatakuliah)
	matakuliah.Post("/", handler.BuatMatakuliah)
	matakuliah.Get("/:id", handler.DetailMatakuliah)
	matakuliah.Put("/:id", handler.PerbaruiMatakuliah)
	matakuliah.Delete("/:id", handler.HapusMatakuliah)
	
}