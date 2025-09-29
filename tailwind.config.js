export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          DEFAULT: "#2563EB",   // Primary (Blue 600)
          50: "#EFF6FF",
          100: "#DBEAFE",
          600: "#2563EB",
          700: "#1D4ED8",
        },
        ink: {
          900: "#0F172A",
          700: "#334155",
        },
      },
      borderRadius: {
        xl: "0.9rem",
        "2xl": "1.25rem",
      },
      boxShadow: {
        card: "0 8px 24px rgba(15, 23, 42, 0.06)",
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
