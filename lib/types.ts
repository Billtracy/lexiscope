export interface Section {
  id: string;
  number: string;
  marginal_title: string;
  text: string;
  keywords?: string[];
  citations?: string[];
  amendments?: string[];
  notes?: string;
}

export interface Chapter {
  id: string;
  title: string;
  sections: Section[];
}

export interface Part {
  id: string;
  title: string;
  chapters: Chapter[];
}

export interface Constitution {
  jurisdiction: string;
  title: string;
  version: string;
  parts: Part[];
}

export interface CrossRefs {
  [sectionId: string]: {
    related_sections: string[];
    topics: string[];
    case_law: Array<{ name: string; citation?: string; url?: string }>;
  };
}

export interface Tags {
  [tag: string]: string[];
}
