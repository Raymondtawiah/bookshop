<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        Like::query()->delete();
        Comment::query()->delete();
        Announcement::query()->delete();

        $first = Announcement::create([
            'title' => 'You’ve got this',
            'message' => 'A viral visa confidence boost to keep you going.',
            'author_name' => 'VisaPrep Team',
            'author_initials' => 'VP',
            'video_url' => '/announcement-videos/Youvegotthisviralvisafyp_1790862068436.mp4',
            'video_duration' => '1:00',
            'is_new' => true,
            'published_at' => now()->subHours(2),
        ]);

        $second = Announcement::create([
            'title' => 'Why did you choose this school?',
            'message' => 'This is NOT the time to give a generic answer. Here is how to nail this question.',
            'author_name' => 'VisaPrep Team',
            'author_initials' => 'VP',
            'video_url' => '/announcement-videos/WhydidyouchoosethisschoolThisisNOTthetimetogiveage_1790861717289.mp4',
            'video_duration' => '2:20',
            'is_new' => true,
            'published_at' => now()->subHours(4),
        ]);

        $third = Announcement::create([
            'title' => 'Day 1: Understanding your Visa Interview',
            'message' => 'Start your visa interview preparation with the basics. Learn what officers really look for.',
            'author_name' => 'VisaPrep Team',
            'author_initials' => 'VP',
            'video_url' => '/announcement-videos/Day1UnderstandingyourVisainterviewfypvisavisawithn_1790861771751 (1).mp4',
            'video_duration' => '3:45',
            'is_new' => true,
            'published_at' => now()->subHours(5),
        ]);

        $fourth = Announcement::create([
            'title' => 'Know this about your student visa interview',
            'message' => 'Key insights every student visa applicant should know before the interview.',
            'author_name' => 'VisaPrep Team',
            'author_initials' => 'VP',
            'video_url' => '/announcement-videos/Knowthisaboutyourstudentsvisainterviewvisastudentv_1790861613145.mp4',
            'video_duration' => '2:50',
            'is_new' => false,
            'published_at' => now()->subDays(1),
        ]);

        $fifth = Announcement::create([
            'title' => 'Congratulations to you in advance',
            'message' => 'A quick motivational message from Visa with Nathaniel officer Charles.',
            'author_name' => 'VisaPrep Team',
            'author_initials' => 'VP',
            'video_url' => '/announcement-videos/Congratstoyouinadvancevisawithnathanielofficerchar_1790861863541 (1).mp4',
            'video_duration' => '2:10',
            'is_new' => true,
            'published_at' => now()->subHours(8),
        ]);
    }
}
