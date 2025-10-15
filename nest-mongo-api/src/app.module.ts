import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { AppService } from './app.service';
import * as dotenv from 'dotenv';
import { MongooseModule } from '@nestjs/mongoose';
import { DiariesModule } from './diaries/diaries.module';

dotenv.config();

@Module({
    imports: [
        MongooseModule.forRoot(process.env.MONGODB_URI as string),
        DiariesModule,
    ],
    controllers: [AppController],
    providers: [AppService],
})
export class AppModule {}
