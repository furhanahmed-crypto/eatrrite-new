"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { motion } from "framer-motion";

const ease = [0.22, 1, 0.36, 1];

export function Hero({ data }) {
  const [index, setIndex] = useState(0);

  useEffect(() => {
    const timer = setInterval(() => {
      setIndex((current) => (current + 1) % data.slides.length);
    }, 5000);
    return () => clearInterval(timer);
  }, [data.slides.length]);

  return (
    <section className="relative min-h-[70vh] overflow-hidden text-white md:min-h-[85vh]">
      {data.slides.map((slide, i) => (
        <div
          key={slide}
          className={`absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ${
            i === index ? "opacity-100" : "opacity-0"
          }`}
          style={{ backgroundImage: `url('${slide}')` }}
        />
      ))}
      <div className="absolute inset-0 bg-[linear-gradient(90deg,rgba(1,78,78,0.82),rgba(1,78,78,0.35))]" />
      <div className="container-er relative z-10 flex min-h-[70vh] items-center py-20 md:min-h-[85vh]">
        <div className="max-w-[780px] space-y-6">
          <motion.div
            initial={{ opacity: 0, y: 28 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, ease, delay: 0.35 }}
          >
            <PillLabel light>{data.pill}</PillLabel>
          </motion.div>
          <SplitTitle
            as="h1"
            hero
            className="max-w-[14em] text-[clamp(32px,4.6vw,56px)] font-bold leading-[1.15] text-white"
          >
            {data.title}
          </SplitTitle>
          <motion.p
            className="max-w-[52ch] text-[clamp(15px,1.5vw,17px)] leading-[1.65] text-white/90"
            initial={{ opacity: 0, y: 28 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, ease, delay: 0.45 }}
          >
            {data.text}
          </motion.p>
          <motion.div
            className="flex flex-wrap items-center gap-4"
            initial={{ opacity: 0, y: 28 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, ease, delay: 0.55 }}
          >
            <Link
              href={data.primaryCta.href}
              className="inline-flex h-11 items-center rounded-full bg-gold px-5 font-heading text-[15px] font-semibold text-ink transition hover:-translate-y-0.5 hover:bg-[#f0c96a] min-[400px]:h-12 min-[400px]:px-7 min-[400px]:text-base"
            >
              {data.primaryCta.label}
            </Link>
            <Link
              href={data.secondaryCta.href}
              className="inline-flex h-11 items-center rounded-full border border-white/30 px-5 font-heading text-[15px] font-semibold text-white transition hover:-translate-y-0.5 hover:bg-white hover:text-brand min-[400px]:h-12 min-[400px]:px-7 min-[400px]:text-base"
            >
              {data.secondaryCta.label}
            </Link>
          </motion.div>
          <ul className="flex flex-wrap gap-x-8 gap-y-4 border-t border-white/20 pt-7">
            {data.stats.map((stat) => {
              const Comp = stat.href ? "a" : "li";
              return (
                <Comp
                  key={stat.label}
                  href={stat.href}
                  className="min-w-[7.5rem] list-none"
                >
                  <strong className="block font-heading text-[22px] leading-none text-gold md:text-[26px]">
                    {stat.value}
                  </strong>
                  <span className="mt-1.5 block text-[11px] tracking-[0.06em] text-white/70 uppercase">
                    {stat.label}
                  </span>
                </Comp>
              );
            })}
          </ul>
        </div>
      </div>
    </section>
  );
}
