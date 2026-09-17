"use client";

import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import { Clock, Mail, Menu, Phone, X } from "lucide-react";
import { Button } from "@/shared/ui/button";
import { ThemeToggle } from "@/shared/components/ThemeToggle";
import { siteConfig } from "@/config/site";

const links = [
  { href: "/", label: "Home", key: "home" },
  { href: "/about", label: "About", key: "about" },
  { href: "/programs", label: "Programs", key: "programs" },
  { href: "/pricing", label: "Pricing", key: "pricing" },
  { href: "/#blog", label: "Blog", key: "blog" },
  { href: "/contact", label: "Contact", key: "contact" },
];

export function SiteHeader({ current = "home" }) {
  const [open, setOpen] = useState(false);

  return (
    <header className="sticky top-0 z-40 border-b border-border-soft bg-background/95 backdrop-blur">
      <div className="hidden bg-brand text-[13px] text-white/90 md:block">
        <div className="container-er flex justify-between gap-4 py-2.5">
          <span className="inline-flex items-center gap-2">
            <Clock className="size-3.5 text-gold" />
            {siteConfig.hours}
          </span>
          <span className="flex gap-5">
            <a
              href={siteConfig.emailHref}
              className="inline-flex items-center gap-2 transition hover:text-gold"
            >
              <Mail className="size-3.5 text-gold" />
              {siteConfig.email}
            </a>
            <a
              href={siteConfig.phoneHref}
              className="inline-flex items-center gap-2 transition hover:text-gold"
            >
              <Phone className="size-3.5 text-gold" />
              {siteConfig.phone}
            </a>
          </span>
        </div>
      </div>
      <div className="container-er flex items-center justify-between gap-3 py-3 min-[400px]:gap-4 min-[400px]:py-4">
        <Link href="/" className="relative h-9 w-36 shrink-0 min-[400px]:h-11 min-[400px]:w-44">
          <Image
            src="/images/logo/logo-horizontal.png"
            alt="Eat Rrite"
            fill
            className="object-contain object-left"
            priority
          />
        </Link>
        <nav className="hidden items-center gap-6 lg:flex">
          {links.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              className={`relative text-[15px] font-medium ${
                current === link.key ? "text-brand" : "text-ink"
              }`}
            >
              {link.label}
              {current === link.key ? (
                <span className="absolute -bottom-1 left-0 h-0.5 w-full bg-gold" />
              ) : null}
            </Link>
          ))}
          {/* <ThemeToggle /> */}
          <Link
            href="/appointment"
            className="inline-flex h-11 items-center rounded-full bg-brand px-7 font-heading text-[15px] font-semibold text-white transition hover:-translate-y-0.5 hover:bg-brand-dark"
          >
            Book Appointment
          </Link>
        </nav>
        <div className="flex items-center gap-2 lg:hidden">
          {/* <ThemeToggle /> */}
          <Button
            type="button"
            variant="outline"
            size="icon"
            aria-label="Open menu"
            onClick={() => setOpen(!open)}
          >
            {open ? <X /> : <Menu />}
          </Button>
        </div>
      </div>
      {open ? (
        <div className="container-er flex flex-col gap-3 pb-4 lg:hidden">
          {links.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              onClick={() => setOpen(false)}
            >
              {link.label}
            </Link>
          ))}
          <Link
            href="/appointment"
            className="inline-flex h-11 items-center justify-center rounded-full bg-brand px-7 font-heading text-[15px] font-semibold text-white"
          >
            Book Appointment
          </Link>
        </div>
      ) : null}
    </header>
  );
}
