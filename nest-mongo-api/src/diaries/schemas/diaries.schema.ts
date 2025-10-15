import { Schema, Prop, SchemaFactory } from '@nestjs/mongoose';
import { Document } from 'mongoose';

// Define the Diary schema
@Schema()
export class Diary extends Document {
    @Prop({ required: true })
    author: string;

    @Prop({ required: true })
    title: string;

    @Prop({ type: Date, default: Date.now })
    datetime: Date;

    @Prop({ required: true })
    content: string;

    // Define the emotions array directly in the Diary schema
    @Prop({
        type: [
            {
                emotion: { type: String, required: true }, // Emotion field
                score: { type: Number, required: true, min: 1, max: 10 }, // Score between 1 and 10
            },
        ],
        required: false,
    })
    emotions: { emotion: string; score: number }[];
}

// Generate the schema factory for Diary
export const DiarySchema = SchemaFactory.createForClass(Diary);
