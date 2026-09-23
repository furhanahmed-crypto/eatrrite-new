import { ClipboardCheck, Handshake, HeartPulse, Sparkles } from "lucide-react";
import { PillLabel } from "@/shared/components/PillLabel";
import { SplitTitle } from "@/shared/components/SplitTitle";
import { Reveal } from "@/shared/components/Reveal";

const icons = [ClipboardCheck, HeartPulse, Sparkles, Handshake];

export function CohortSteps({ data }) {
  return (
    <section id="how-it-works" className="bg-brand/5 py-16 md:py-24">
      <div className="container-er space-y-10">
        <div className="mx-auto flex max-w-3xl flex-col items-center gap-3 text-center">
          <Reveal y={24} duration={0.7}>
            <PillLabel>{data.pill}</PillLabel>
          </Reveal>
          <SplitTitle className="text-3xl text-brand md:text-4xl">
            {data.title}
          </SplitTitle>
        </div>
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {data.steps.map((step, index) => {
            const Icon = icons[index] || ClipboardCheck;
            return (
              <Reveal key={step.num} y={56} duration={0.9}>
                <article className="relative h-full overflow-hidden rounded-[24px] border border-border-soft bg-surface p-6 shadow-er">
                  <span className="absolute top-4 right-4 text-4xl font-bold text-brand/10">
                    {step.num}
                  </span>
                  <div className="mb-3 flex items-center gap-3">
                    <div className="grid size-11 shrink-0 place-items-center rounded-2xl bg-mint text-brand">
                      <Icon className="size-5" />
                    </div>
                    <h3 className="text-xl">{step.title}</h3>
                  </div>
                  <p className="text-sm text-soft">{step.text}</p>
                </article>
              </Reveal>
            );
          })}
        </div>
      </div>
    </section>
  );
}
