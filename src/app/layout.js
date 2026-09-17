import { Lora, Bricolage_Grotesque } from "next/font/google";
import { ThemeProvider } from "@/shared/components/ThemeProvider";
import "./globals.css";

const lora = Lora({
  subsets: ["latin"],
  variable: "--font-heading",
});

const bricolage = Bricolage_Grotesque({
  subsets: ["latin"],
  variable: "--font-body",
});

export const metadata = {
  title: {
    default: "Eat Rrite - Nutritionist, Holistic Health and Wellness",
    template: "%s | Eat Rrite",
  },
  description:
    "Eat Rrite is a nutrition and holistic wellness platform offering personalised diet programs, yoga, and lifestyle guidance.",
  icons: {
    icon: "/images/favicon.ico",
  },
};

export default function RootLayout({ children }) {
  return (
    <html lang="en" suppressHydrationWarning className="h-full">
      <body
        className={`${lora.variable} ${bricolage.variable} min-h-full flex flex-col`}
      >
        <ThemeProvider>{children}</ThemeProvider>
      </body>
    </html>
  );
}
