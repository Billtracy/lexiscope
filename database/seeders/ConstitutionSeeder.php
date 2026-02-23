<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ConstitutionNode;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ConstitutionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a law student reviewer user
        $user = User::factory()->create([
            'name' => 'Law Student 004',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Create the Chapter parent node
        $chapter = ConstitutionNode::create([
            'type' => 'chapter',
            'chapter_number' => '4',
            'chapter_title' => 'Fundamental Rights',
            'status' => 'published',
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);

        // 3. Create the Draft Section (Wait for Law Student Review)
        $section = ConstitutionNode::create([
            'type' => 'subsection',
            'parent_id' => $chapter->id,
            'chapter_number' => '4',
            'chapter_title' => 'Fundamental Rights',
            'section_number' => '33',
            'section_title' => 'Right to Life',
            'subsection_number' => '(2)',
            'legal_text' => 'A person shall not be regarded as having been deprived of his life in contravention of this section, if he dies as a result of the use, to such extent and in such circumstances as are permitted by law, of such force as is reasonably necessary - (a) for the defence of any person from unlawful violence or for the defence of property...',
            'plain_english' => 'Killing someone is not considered a crime if it happens while reasonably defending yourself, your property, or stopping a riot.',
            'keywords' => ["self-defense", "riot", "arrest", "fundamental rights"],
            'status' => 'ai_generated',
        ]);

        // 4. Attach Cross References
        $section->caseLaws()->createMany([
            [
                'case_title' => 'Musa v. State',
                'citation' => '(2009) 15 NWLR (Pt. 1165)',
                'relevance_summary' => 'The Supreme Court clarified that self-defense must be proportionate to the threat faced.',
                'url' => 'https://legal-database.ng/cases/musa-v-state',
            ],
            [
                'case_title' => 'Ahmed v. The State',
                'citation' => '(1999) 7 SC (Pt II) 1',
                'relevance_summary' => 'Establishes that the burden of proving self-defense rests on the accused.',
                'url' => 'https://legal-database.ng/cases/ahmed-v-state',
            ]
        ]);

        $section->internationalComparisons()->createMany([
            [
                'country' => 'USA',
                'constitution_provision' => '2nd Amendment / Self-Defense Doctrine',
                'similarity_note' => 'Unlike the Nigerian constitution which explicitly lists exceptions to right to life, US self-defense is largely established through case law (Castle Doctrine).',
                'related_link' => 'https://usconstitution.net/amend2',
            ],
            [
                'country' => 'India',
                'constitution_provision' => 'Article 21',
                'similarity_note' => 'India\'s Article 21 is broader (\'Procedure established by law\') but covers similar grounds regarding deprivation of life.',
                'related_link' => 'https://indiankanoon.org/doc/1199182/',
            ]
        ]);

        $this->command->info('Seeded user student@example.com (password) and draft dataset.');
    }
}
