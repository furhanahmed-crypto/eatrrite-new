"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { Input } from "@/shared/ui/input";
import { Label } from "@/shared/ui/label";
import { Button } from "@/shared/ui/button";

export function AdminLoginForm() {
  const router = useRouter();
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  async function handleSubmit(event) {
    event.preventDefault();
    setLoading(true);
    setError("");
    try {
      const res = await fetch("/api/admin/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ password }),
      });
      const data = await res.json();
      if (!data.ok) throw new Error(data.error || "Login failed");
      router.replace("/admin/appointments");
      router.refresh();
    } catch (err) {
      setError(err.message || "Incorrect password.");
      setLoading(false);
    }
  }

  return (
    <form onSubmit={handleSubmit} className="mx-auto max-w-sm space-y-4 rounded-3xl border border-border-soft bg-surface p-8 shadow-sm">
      <p className="text-xs uppercase tracking-[0.2em] text-brand">
        Eat Rrite
      </p>
      <h1 className="font-heading text-3xl text-ink">
        Consultant calendar
      </h1>
      <p className="text-sm text-soft">
        Sign in to review booked consultations.
      </p>
      {error ? <p className="text-sm text-destructive">{error}</p> : null}
      <div className="space-y-2">
        <Label htmlFor="password">Password</Label>
        <Input
          id="password"
          type="password"
          autoFocus
          autoComplete="current-password"
          required
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
      </div>
      <Button type="submit" disabled={loading} className="w-full rounded-full">
        {loading ? "Signing in…" : "Open calendar"}
      </Button>
    </form>
  );
}
