import { Constitution, CrossRefs, Tags } from './types';
import constitutionData from '../data/constitution.ng.json';
import crossRefData from '../data/crossrefs.ng.json';
import tagData from '../data/tags.ng.json';

export async function loadConstitution(): Promise<Constitution> {
  return constitutionData as unknown as Constitution;
}

export async function loadCrossRefs(): Promise<CrossRefs> {
  return crossRefData as unknown as CrossRefs;
}

export async function loadTags(): Promise<Tags> {
  return tagData as unknown as Tags;
}
