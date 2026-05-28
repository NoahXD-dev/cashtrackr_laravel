import { CircularProgressbar, buildStyles } from 'react-circular-progressbar'
import 'react-circular-progressbar/dist/styles.css'

type Props = {
    percentageUsed: number
}

export default function ProgressBar({ percentageUsed }: Props) {
    return (
        <CircularProgressbar 
            value={percentageUsed}
            styles={buildStyles({
                pathColor:'#3C0C66',
                trailColor: '#F5F5F5',
                textSize: 8,
                textColor: '#3C0C66'
            })}
            text={`${percentageUsed}% Gastado`}
        />
    )
}