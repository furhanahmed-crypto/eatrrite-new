import { SiteHeader } from "@/shared/components/SiteHeader";
import { SiteFooter } from "@/shared/components/SiteFooter";
import { FloatActions } from "@/shared/components/FloatActions";

export function SiteShell({ current, children }) {
  return (
    <>
      <SiteHeader current={current} />
      <main className="flex-1">{children}</main>
      <SiteFooter />
      <FloatActions />
    </>
  );
}
