import { z } from 'zod';

export const SectionSchema = z.object({
  id: z.string(),
  number: z.string(),
  marginal_title: z.string(),
  text: z.string(),
  keywords: z.array(z.string()).optional(),
  citations: z.array(z.string()).optional(),
  amendments: z.array(z.string()).optional(),
  notes: z.string().optional(),
});

export const ChapterSchema = z.object({
  id: z.string(),
  title: z.string(),
  sections: z.array(SectionSchema),
});

export const PartSchema = z.object({
  id: z.string(),
  title: z.string(),
  chapters: z.array(ChapterSchema),
});

export const ConstitutionSchema = z.object({
  jurisdiction: z.string(),
  title: z.string(),
  version: z.string(),
  parts: z.array(PartSchema),
});

export type Constitution = z.infer<typeof ConstitutionSchema>;
