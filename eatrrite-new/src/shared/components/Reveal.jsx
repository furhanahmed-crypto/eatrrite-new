"use client";

import { useRef } from "react";
import { motion, useInView } from "framer-motion";

const ease = [0.22, 1, 0.36, 1];

/** Fade-up on scroll into view. */
export function Reveal({
  children,
  className,
  y = 48,
  duration = 0.85,
  delay = 0,
  amount = 0.28,
}) {
  const ref = useRef(null);
  const inView = useInView(ref, {
    amount,
    once: true,
    margin: "0px 0px -20% 0px",
  });

  return (
    <motion.div
      ref={ref}
      className={className}
      initial={{ opacity: 0, y }}
      animate={inView ? { opacity: 1, y: 0 } : { opacity: 0, y }}
      transition={{ duration, ease, delay }}
    >
      {children}
    </motion.div>
  );
}
