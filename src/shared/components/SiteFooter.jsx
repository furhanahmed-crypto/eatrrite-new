import Image from "next/image";
import Link from "next/link";
import { siteConfig } from "@/config/site";
import { programs } from "@/constants/programs/programs";

export function SiteFooter() {
  return (
    <footer className="mt-auto bg-footer text-white">
      <div className="container-er grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
        <div className="space-y-4">
          <div className="relative h-9 w-36 min-[400px]:h-10 min-[400px]:w-44">
            <Image
              src="/images/logo/logo-horizontal-light.png"
              alt="Eat Rrite"
              fill
              className="object-contain object-left"
            />
          </div>
          <p className="text-white/75">
            Science-backed, culturally-rooted nutrition coaching — so you learn
            how to Eat Rrite for life, without crash diets.
          </p>
          <p className="text-sm text-white/60">
            {siteConfig.address1} &amp; {siteConfig.address2}
            <br />
            <small>{siteConfig.locationsNote}</small>
          </p>
        </div>
        <div>
          <h3 className="text-white mb-4 font-heading text-lg">Quick Links</h3>
          <ul className="space-y-2 text-white/75">
            <li>
              <Link href="/">Home</Link>
            </li>
            <li>
              <Link href="/about">About Us</Link>
            </li>
            <li>
              <Link href="/programs">Programs</Link>
            </li>
            <li>
              <Link href="/pricing">Pricing</Link>
            </li>
            <li>
              <Link href="/appointment">Appointment</Link>
            </li>
            <li>
              <Link href="/contact">Contact</Link>
            </li>
          </ul>
        </div>
        <div>
          <h3 className="mb-4 font-heading text-lg text-white">Programs</h3>
          <ul className="space-y-2 text-white/75">
            {programs.map((program) => (
              <li key={program.slug}>
                <Link href={`/programs/${program.slug}`}>{program.short}</Link>
              </li>
            ))}
          </ul>
        </div>
        <div className="space-y-3 text-white/75">
          <h3 className="mb-4 font-heading text-lg text-white">Get In Touch</h3>
          <p>
            <a href={siteConfig.emailHref}>{siteConfig.email}</a>
          </p>
          <p>
            <a href={siteConfig.phoneHref}>{siteConfig.phone}</a>
          </p>
          <p>{siteConfig.hours}</p>
        </div>
      </div>
      <div className="container-er flex flex-wrap justify-between gap-2 border-t border-white/10 py-5 text-sm text-white/55">
        <span>
          © {new Date().getFullYear()} Eat Rrite. All rights reserved.
        </span>
        <span>Learn to Eat Rrite.</span>
      </div>
    </footer>
  );
}
