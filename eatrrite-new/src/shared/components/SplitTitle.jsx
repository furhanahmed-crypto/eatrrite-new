"use client";

import { useMemo, useRef } from "react";
import { motion, useInView } from "framer-motion";
import { cn } from "@/lib/utils";

const ease = [0.22, 1, 0.36, 1];

function buildChars(text) {
  const words = text.trim().split(/\s+/);
  let index = 0;
  return words.map((word, wi) => ({
    word,
    wi,
    chars: Array.from(word).map((char) => {
      const delayIndex = index;
      index += 1;
      return { char, delayIndex };
    }),
  }));
}

/** Letter-stagger titles for section headings. */
export function SplitTitle({
  as: Tag = "h2",
  children,
  className,
  hero = false,
}) {
  const text = String(children ?? "");
  const words = useMemo(() => buildChars(text), [text]);
  const ref = useRef(null);
  const inView = useInView(ref, {
    amount: 0.28,
    once: true,
    margin: "0px 0px -20% 0px",
  });
  const play = hero || inView;

  return (
    <Tag
      ref={ref}
      className={cn("split-title is-ready", className)}
      aria-label={text}
    >
      <span className="split-line">
        {words.map(({ word, wi, chars }) => (
          <span key={`${word}-${wi}`} className="split-word">
            {chars.map(({ char, delayIndex }) => (
              <motion.span
                key={`${wi}-${delayIndex}`}
                className="split-char"
                initial={{ y: "110%", opacity: 0 }}
                animate={
                  play ? { y: "0%", opacity: 1 } : { y: "110%", opacity: 0 }
                }
                transition={{
                  duration: 0.7,
                  ease,
                  delay: (hero ? 0.15 : 0) + delayIndex * 0.022,
                }}
              >
                {char}
              </motion.span>
            ))}
          </span>
        ))}
      </span>
    </Tag>
  );
}
