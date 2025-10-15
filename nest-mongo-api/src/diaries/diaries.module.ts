import { Module } from '@nestjs/common';
import { DiariesService } from './diaries.service';
import { DiariesController } from './diaries.controller';
import { MongooseModule } from '@nestjs/mongoose';
import { DiarySchema } from './schemas/diaries.schema';

@Module({
    imports: [
        MongooseModule.forFeature([{ name: 'Diary', schema: DiarySchema }]),
    ],
    controllers: [DiariesController],
    providers: [DiariesService],
})
export class DiariesModule {}
