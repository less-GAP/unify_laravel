export function listProcess() {
    return [
        { value: 0, label: 'Waiting' },
        { value: 1, label: 'Eligibility Check' },
        { value: 2, label: 'Approve new patient' },
    ]
}

export function getProcess(value) {
    const list = listProcess()
    return list.find(item => item.value === value)
}