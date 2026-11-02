package middleware

import (
	"crypto/subtle"

	"github.com/gofiber/fiber/v3"
)

// Memeriksa header X-Api-Key terhadap nilai dari .env
func KunciApi(kunci string) fiber.Handler {
	return func(c fiber.Ctx) error {
		diterima := c.Get("X-Api-Key")

		if kunci == "" || subtle.ConstantTimeCompare([]byte(diterima), []byte(kunci)) != 1 {
			return fiber.NewError(fiber.StatusUnauthorized, "API key tidak valid atau tidak ada")
		}

		return c.Next()
	}
}